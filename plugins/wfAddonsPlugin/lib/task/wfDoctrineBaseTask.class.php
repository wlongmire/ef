<?php
abstract class wfDoctrineBaseTask extends sfDoctrineBaseTask
{
	protected $databaseManager = null;

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
   * Overwriting doRun to add listeners for logging. It's kind of a hack but it's not terrible.
   * @param sfCommandManager $commandManager
   * @param array $options
   * @return mixed parent::doRun
   */
  protected function doRun(sfCommandManager $commandManager, $options)
  {
    $this->dispatcher->connect('command.filter_options', array($this, 'addFileLoggerOption'));
    $this->dispatcher->connect('command.pre_command', array($this, 'enableFileLogger'));
    $result = parent::doRun($commandManager, $options);
// This used to be here but I think it needs to be deleted???
//    if ($this->configuration instanceof sfApplicationConfiguration)
//    {
//      sfContext::createInstance($this->configuration);
//    }
    return $result;
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

  public function loadDatabaseManager()
  {
  	if ($this->databaseManager == null)
  	{
      $this->databaseManager = new sfDatabaseManager($this->configuration);  		
  	}  	
  }

  public function getDbUsername()
	{
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    return $conn->getOption('username');
	}
	
	public function getDbPassword()
	{
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    return $conn->getOption('password');		
	}
	
	public function getDbHost()
	{
		$conn = Doctrine_Manager::getInstance()->getCurrentConnection();    
    preg_match('/host=([\w\.]+)/', $conn->getOption('dsn'), $matches);
    return $matches[1];
	}
	
	public function getDbName()
	{
    $conn = Doctrine_Manager::getInstance()->getCurrentConnection();    
    preg_match('/dbname=(\w+)/', $conn->getOption('dsn'), $matches);
    return $matches[1];  		
	}
	
	public function getHostname()
	{
		$hostname = php_uname('n');
		if ($hostname == false)
		{
			throw new UnexpectedValueException('Unable to get current hostname using php_uname');
		}
		return $hostname;
	}
	
	/**
   * Execute command and log the error message, if there is one
   * @param string $command
   * @param boolean $dryRun True if the command should just be logged, not executed (default: false)
   * @return array The output from execute()
   */
  protected function logExecute($command, $dryRun = false)
  {
    if ($dryRun)
    {
      $this->logSection('DRY RUN exec ', $command);
      return array(null, null);
    }
    else
    {
      list($output, $error) = $this->getFilesystem()->execute($command);
      if (trim($error))
      {
        $this->log($error);
      }
      if (trim($output))
      {
        $this->log($output);
      }
      return array($output, $error);
    }
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
   * @return boolean True if we obtained the lock. False if the lock was unavailable (already locked by something else).
   */
  protected function getExclusiveLock()
  {
    $this->logSection($this->getName(), 'Attempting to get exclusive lock.');
    $this->exclusiveLockFile = fsOverseerTools::getExclusiveLock($this->getFullName());
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
   */
  protected function requireExclusiveLock()
  {
    if (!$this->getExclusiveLock())
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
}
