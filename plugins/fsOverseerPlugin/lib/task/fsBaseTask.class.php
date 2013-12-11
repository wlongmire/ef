<?php
abstract class fsBaseTask extends sfBaseTask
{
  protected $defaultProperties = array(
    'snapshot_path' => 'data/snapshots',
    'snapshot_sync_path' => 'data/sync'
  );

  /**
   * Extended to increase the formatter line size so it doesn't truncate lines
   * @param sfEventDispatcher $dispatcher
   * @param sfFormatter $formatter
   */
  public function __construct(sfEventDispatcher $dispatcher, sfFormatter $formatter)
  {
    $formatter->setMaxLineSize(999999);
    parent::__construct($dispatcher, $formatter);
  }

  /**
   * Overwritten to add --all-apps option and run task for each existing application
   * Also added --log option.
   *
   * @param sfCommandManager $commandManager
   * @param array $options
   * @return mixed
   */
  protected function doRun(sfCommandManager $commandManager, $options)
  {
    $this->dispatcher->connect('command.filter_options', array($this, 'addFileLoggerOption'));
    $this->dispatcher->connect('command.pre_command', array($this, 'enableFileLogger'));

    $commandManager->getOptionSet()->addOption(
      new sfCommandOption('all-apps', null, sfCommandOption::PARAMETER_NONE, 'Run the task for each application.')
    );

    $event = $this->dispatcher->filter(new sfEvent($this, 'command.filter_options', array('command_manager' => $commandManager)), $options);
    $options = $event->getReturnValue();

    $this->process($commandManager, $options);

    $event = new sfEvent($this, 'command.pre_command', array('arguments' => $commandManager->getArgumentValues(), 'options' => $commandManager->getOptionValues()));
    $this->dispatcher->notifyUntil($event);
    if ($event->isProcessed())
    {
      return $event->getReturnValue();
    }

    $this->checkProjectExists();

    if ($commandManager->getOptionValue('all-apps'))
    {
      $retValues = array();
      $apps = sfFinder::type('dir')->discard('.sf')->maxdepth(0)->relative()->in(sfConfig::get('sf_apps_dir'));

      foreach($apps as $app)
      {
        $this->logSection('APPLICATION', $app);
        $this->setupForApp($commandManager, $app);
        try
        {
          $retValues[$app] = $this->execute($commandManager->getArgumentValues(), $commandManager->getOptionValues());
        }
        catch (Exception $e)
        {
          $retValues[$app] = 'Error: ' . $e->__toString();
        }
        $this->resetForNextApp();
      }

      // if there are any non-false values, echo everything and return -1 (will cause cron to email us). otherwise, return 0 for success
      $ret = 0;
      if (array_filter($retValues))
      {
        $valuesAsString = var_export($retValues, true);
        $this->logBlock('Some tasks did not run successfully. Output: ' . $valuesAsString, 'ERROR');
        $ret = -1;
      }
    }
    else
    {
      $this->setupForApp($commandManager);
      $ret = $this->execute($commandManager->getArgumentValues(), $commandManager->getOptionValues());
    }

    $this->dispatcher->notify(new sfEvent($this, 'command.post_command'));

    return $ret;
  }

  /**
   * Create configuration for application (originally taken from sfBaseTask::doRun())
   *
   * @param sfCommandManager $commandManager
   * @param string $application
   */
  protected function setupForApp(sfCommandManager $commandManager, $application = null)
  {
    if($application)
    {
      // THIS DOESNT WORK AND ITS A PAIN IN THE ASS TO MAKE IT WORK
      // You'll just have to stick with using options...
//      if ($commandManager->getArgumentSet()->hasArgument('application'))
//      {
//        $commandManager->setArgumentValue('application', $application);
//      }
      if ($commandManager->getOptionSet()->hasOption('application'))
      {
        $commandManager->setOption($commandManager->getOptionSet()->getOption('application'), $application);
      }
    }

    $requiresApplication = $commandManager->getArgumentSet()->hasArgument('application') || $commandManager->getOptionSet()->hasOption('application');
    if (null === $this->configuration || ($requiresApplication && !$this->configuration instanceof sfApplicationConfiguration))
    {
      if (!$application)
      {
        $application = $commandManager->getArgumentSet()->hasArgument('application') ? $commandManager->getArgumentValue('application') : null;
        if ($application === null)
        {
          $application = $commandManager->getOptionSet()->hasOption('application') ? $commandManager->getOptionValue('application') : null;
        }
        if (true === $application)
        {
          $application = $this->getFirstApplication();

          if ($commandManager->getOptionSet()->hasOption('application'))
          {
            $commandManager->setOption($commandManager->getOptionSet()->getOption('application'), $application);
          }
        }
      }

      $env = $commandManager->getOptionSet()->hasOption('env') ? $commandManager->getOptionValue('env') : 'test';
      $this->configuration = $this->createConfiguration($application, $env);
    }

    if (null !== $this->commandApplication && !$this->commandApplication->withTrace())
    {
      sfConfig::set('sf_logging_enabled', false);
    }
  }

  /**
   * Reset for the next application. Right now, that means:
   * - Unset the configuration
   * - Shutdown the db manager, if there is one
   */
  protected function resetForNextApp()
  {
    if ($this->databaseManager)
    {
      $this->databaseManager->shutdown();
      $this->databaseManager = null;
    }
    else
    {
      // this is kind of a hack for doctrine:migrate, but it may prove useful for other tasks too
      $databaseManager = new sfDatabaseManager($this->configuration);
      $databaseManager->shutdown();
    }
    $this->configuration = null;
  }

  /**
   * Add option to enable file logging
   * @param sfEvent $event
   * @return mixed Returns the options passed to it without altering them
   */
  public function addFileLoggerOption(sfEvent $event, $options)
  {
    $parameters = $event->getParameters();
    $optionSet = $parameters['command_manager']->getOptionSet();
    if (!$optionSet->hasOption('log'))
    {
      $optionSet->addOption(new sfCommandOption('log', null, sfCommandOption::PARAMETER_NONE, 'Log output to a logfile'));
    }

    $this->dispatcher->disconnect('command.filter_options', $this);

    return $options;
  }

  /**
   * Enable file logging if option is set
   */
  public function enableFileLogger(sfEvent $event)
  {
    $parameters = $event->getParameters();
    if ($parameters['options']['log'])
    {
      $fileLogger = new sfFileLogger($this->dispatcher, array(
          'file' => ProjectConfiguration::guessRootDir() . '/log/' . sfConfig::get('sf_app') . '_' . $this->getFullName() .  '-task.log',
          'format' => '%time% %message%%EOL%'
      ));
      $this->dispatcher->connect('command.log', array($fileLogger, 'listenToLogEvent'));
    }
  }

  /**
   * Get server properties from config/properties.ini
   * @param string $server the server name
   * @param array $requiredProperties throw an error if any of these properties are not present and dont have a default
   * @return array server properties
   * @throws RuntimeException if the server is not present or if any of the required properties are missing
   */
  protected function getServerProperties($server, $requiredProperties = array())
  {
    $servers = parse_ini_file($this->configuration->getRootDir() . '/config/properties.ini', true);
    if (!isset($servers[$server]))
    {
      throw new RuntimeException('Server "' . $server . '" does not exist in config/properties.ini');
    }

    $properties = array_merge($servers[$server], $this->defaultProperties);

    $this->ensureRequiredProperties($properties, $requiredProperties);

    return $properties;
  }


  protected function ensureRequiredProperties($properties, $requiredProperties = array())
  {
    foreach($requiredProperties as $property)
    {
      if (!isset($properties[$property]))
      {
        throw new RuntimeException('"' . $property . '" must be set in config/properties.ini');
      }
    }
  }


  /**
   * Build a the connection string to use with ssh or rsync
   * @param string $host
   * @param string $dir
   * @param string $user
   * @param string $target
   * @return string user@host:dir/target
   */
  protected function getConnectString($host, $dir = null, $user = null, $target = null)
  {
    if ($target && $dir && strpos($target,DIRECTORY_SEPARATOR) !== 0)
    {
      $target = $dir . DIRECTORY_SEPARATOR . $target;
    }
    else if (!$target)
    {
      $target = $dir;
    }

    return ($user ? $user . '@' : '') . $host . ($target ? ':' . $target : '') ;
  }


  /**
   * Gets the server properties for the
   * @return <type>
   */
  protected function getThisServerProperties($requiredProperties = array())
  {
    return $this->getServer(php_uname('n'), $this->configuration->getRootDir(),  $requiredProperties);
  }


  /**
   * Search config/properties.ini for a server with the given hostname and dir.
   * @param string $hostname
   * @param string $dir
   * @return string the server name
   * @throws InvalidArgumentException if there is no matching server
   */
  protected function getServer($hostname, $dir, $requiredProperties = array())
  {
    $servers = parse_ini_file($this->configuration->getRootDir() . '/config/properties.ini', true);
    foreach($servers as $server => $properties)
    {
      if (isset($properties['hostname']) && $properties['hostname'] == $hostname &&
          isset($properties['dir']) && $properties['dir'] == $dir)
      {
        $properties = array_merge($properties, $this->defaultProperties);
        $this->ensureRequiredProperties($properties, $requiredProperties);
        return array($server, $properties);
      }
    }

    throw new InvalidArgumentException('Could not find matching server in properties.ini for ' . $hostname . ' and ' . $dir);
  }


  /**
   * @return boolean True if production safety checks are on and this is the production instance. False otherwise.
   */
  protected function isProduction()
  {
    return sfConfig::get('app_fs_overseer_plugin_production_safety_check', true) && fsOverseerTools::isProductionHost();
  }


  /**
   * Overwrites sfCommandApplicationTask::runTask() to use createTaskWithDefaultOptions
   * @param string $name
   * @param array $arguments
   * @param array $options
   * @return mixed the exit code of the task after it runs
   */
  protected function runTask($name, $arguments = array(), $options = array())
  {
    return $this->createTaskWithDefaultOptions($name)->run($arguments, $options);
  }


  /**
   * An alternative for sfCommandApplicationTaskcreateTask which adds in the default options so they can
   * be used when the task is run from inside another task
   * @param string $name
   * @return sfTask a task
   * @see sfCommandApplicationTask::createTask()
   */
  protected function createTaskWithDefaultOptions($name)
  {
    $task = $this->createTask($name);

    if ($task instanceof sfCommandApplicationTask)
    {
      $optionNames = array_map(function($item) {return $item->getName();}, $task->getOptions());

      if (array_search('help', $optionNames) === false)
      {
        $task->addOption('--help',    '-H', sfCommandOption::PARAMETER_NONE, 'Display this help message.');
      }
      if (array_search('quiet', $optionNames) === false)
      {
        $task->addOption('--quiet',   '-q', sfCommandOption::PARAMETER_NONE, 'Do not log messages to standard output.');
      }
      if (array_search('trace', $optionNames) === false)
      {
        $task->addOption('--trace',   '-t', sfCommandOption::PARAMETER_NONE, 'Turn on invoke/execute tracing, enable full backtrace.');
      }
      if (array_search('version', $optionNames) === false)
      {
        $task->addOption('--version', '-V', sfCommandOption::PARAMETER_NONE, 'Display the program version.');
      }
      if (array_search('color', $optionNames) === false)
      {
        $task->addOption('--color',   '',   sfCommandOption::PARAMETER_NONE, 'Forces ANSI color output.');
      }
    }

    return $task;
  }

  /**
   * Logs a message with the ERROR style
   * @param string $message
   */
  protected function logError($message)
  {
    $this->logSection($this->getName(), $message, null, 'ERROR');
  }

  /**
   * Gets an exclusive lock for this task. Useful to make sure that only one instance of the task can run at once.
   *
   * Don't forget to call freeExclusiveLock() when you're done
   *
   * @param boolean $perApp True if the lock is on a per-application basis. False if there's one lock for all the applications.
   * @return boolean True if we obtained the lock. False if the lock was unavailable (already locked by something else).
   */
  protected function getExclusiveLock($perApp = true)
  {
    $this->logSection($this->getName(), 'Attempting to get exclusive lock.');
    $this->exclusiveLockFile = fsOverseerTools::getExclusiveLock($this->getFullName(), $perApp);
    if (!$this->exclusiveLockFile)
    {
      $this->logSection($this->getName(), 'Failed to get lock.');
      return false;
    }
    $this->logSection($this->getName(), 'Got lock.');
    return true;
  }

  /**
   * Attempts to get an exclusive lock. Throws an error if the lock is not obtained.
   *
   * @param boolean $perApp True if the lock is on a per-application basis. False if there's one lock for all the applications.
   */
  protected function requireExclusiveLock($perApp = true)
  {
    if (!$this->getExclusiveLock($perApp))
    {
      throw new RuntimeException('Could not get exclusive lock.');
    }
  }

  /**
   * Frees the exclusive lock if it exists.
   */
  protected function freeExclusiveLock()
  {
    if ($this->exclusiveLockFile)
    {
      fsOverseerTools::freeExclusiveLock($this->exclusiveLockFile);
    }
  }

  /**
   * Throw an error if the application option is not set.
   * @param array $options
   */
  protected function requireApplication($options)
  {
    if (!$options['application'])
    {
      throw new InvalidArgumentException('Application required.');
    }
  }
}