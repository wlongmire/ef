<?php
class fsSnapshotTask extends fsBaseTask
{
  protected function configure()
  {
    $this->namespace            = 'fs';
    $this->name                 = 'snapshot';
    $this->briefDescription     = 'Save or loads a snapshot of the database.';

    $this->addArgument('action', sfCommandArgument::REQUIRED, 'Either "save" or "load"');

    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application to run the task under', null);
    $this->addOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application to run the task under', 'frontend'); // HACK BECAUSE WE CAN'T SET ARGUMENTS IN SFCOMMANDMANAGER
    $this->addOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'Environment', 'prod');

    $this->addOption('server', null, sfCommandOption::PARAMETER_REQUIRED, 'The server of the snapshot to load.', 'production');

    $this->addOption('db', null, sfCommandOption::PARAMETER_REQUIRED, 'Shortcut to set both remote-db and local-db options.');
    $this->addOption('remote-db', null, sfCommandOption::PARAMETER_REQUIRED, 'Which database to load from.');
    $this->addOption('local-db', null, sfCommandOption::PARAMETER_REQUIRED, 'Which database to save from or load to.');
    $this->addOption('snapshot', null, sfCommandOption::PARAMETER_OPTIONAL, 'Which snapshot to load.', 'latest.sql');

    $this->addOption('svn', null, sfCommandOption::PARAMETER_NONE, 'Commit the snapshot to SVN after save.');
    $this->addOption('skip-download', null, sfCommandOption::PARAMETER_NONE, 'Skip downloading the latest snapshot before load.');
    $this->addOption('load-to-production', null, sfCommandOption::PARAMETER_NONE,
      'This option is required when loading into the production database.');

    $this->addOption('and-migrate', null, sfCommandOption::PARAMETER_NONE, 'Migrate after loading.');
  }


  protected function execute($arguments = array(), $options = array())
  {
    // Part 2 of the hack above. Options will be set if we used --all-apps, arguments will be set if we specified one app. Error if neither is set.
//    if(!$options['application'] && !$arguments['application'])
//    {
//      throw new InvalidArgumentException('If you don\'t use --all-apps, you must specify an application.');
//    }

    $this->databaseManager = new sfDatabaseManager($this->configuration);

    $options['local-db'] = $options['local-db'] ?: $options['db'];
    $options['remote-db'] = $options['remote-db'] ?: $options['db'];

    list($server, $properties) = $this->getThisServerProperties(array('host', 'dir', 'snapshot_path'));
    $localDb = fsTools::getDatabaseSettings(
      $options['local-db'] ?: (isset($properties['default_db']) ? $properties['default_db'] : null),
      $this->configuration
    );

    if ($arguments['action'] == 'load')
    {
      $this->load($options, $server, $properties, $localDb);
    }
    elseif ($arguments['action'] == 'save')
    {
      $this->save($options, $server, $properties, $localDb);
    }
    else
    {
      throw new InvalidArgumentException(sprintf('Unknown action "%s"', $arguments['action']));
    }
  }


  protected function save($options, $server, $properties, $dbSettings)
  {
    $rootDir = $properties['dir'];
    $saveDir = $rootDir . '/' . $properties['snapshot_path'] . '/' . $server . '/' . $dbSettings['dbname'];
    $filename =  date('Y-m-d_H:i:s') . '.sql';
    $symlink = $saveDir . '/latest.sql';

    $this->getFilesystem()->mkdirs($saveDir, 0755);

    $this->getFilesystem()->execute(sprintf(
      'mysqldump -c -h %s -u %s -p%s %s > %s/%s',
      $dbSettings['host'], $dbSettings['username'], $dbSettings['password'], $dbSettings['dbname'],
      escapeshellarg($saveDir), escapeshellarg($filename)
  	));

    if (file_exists($symlink))
    {
      unlink($symlink);
    }
    symlink($filename, $symlink);

  	if ($options['svn'])
  	{
      $syncDir = $rootDir . '/' . $properties['snapshot_sync_path'] . '/' . $server;
      $syncDbDir = $syncDir . '/' . $dbSettings['dbname'];

      $svnAdd = !file_exists($syncDir);
      $svnAddDb = !$svnAdd && !file_exists($syncDbDir);
      $this->getFilesystem()->mkdirs($syncDbDir, 0775);

      $this->logSection($this->getName(), sprintf('Copying latest snapshot to %s', $syncDir));
      $this->getFilesystem()->execute(sprintf('cp %s %s', escapeshellarg($symlink), escapeshellarg($syncDbDir . '/')));

      $this->logSection($this->getName(), 'Commiting snapshot to svn');
      if ($svnAdd)
      {
        $this->getFilesystem()->execute('svn add ' . escapeshellarg($syncDir));
      }
      if ($svnAddDb)
      {
        $this->getFilesystem()->execute('svn add ' . escapeshellarg($syncDbDir));
      }
      $this->getFilesystem()->execute(sprintf('svn ci %s -m "Added snapshot for %s: %s"', escapeshellarg($syncDir), $dbSettings['dbname'], $filename));
  	}
  }


  protected function load($options, $localServer, $localProperties, $dbSettings)
  {
    if (!$options['server'])
    {
      throw new InvalidArgumentException('Must specify a server');
    }

    $remoteProperties = $this->getServerProperties($options['server'], array('host', 'snapshot_path'));
    $remoteDb = $options['remote-db'] ?: (isset($remoteProperties['default_db']) ? $remoteProperties['default_db'] : $dbSettings['dbname']);

    if ($this->isProduction() && !$options['load-to-production'])
    {
      throw new RuntimeException(
        'Your are trying to load a database into production. Use the --load-to-production flag if you are sure you want to do this');
    }

    $localSnapshot = $localProperties['snapshot_path'] . '/' . $options['server'] .
                     '/' . $remoteDb . '/' . $options['snapshot'];

    if (!$options['skip-download'] && $options['server'] != $localServer)
    {
      $remoteSnapshot = $remoteProperties['snapshot_path'] . '/' . $options['server'] .
                        '/' . $remoteDb . '/' . $options['snapshot'];
      $this->downloadLatestSnapshot($options['server'], $remoteSnapshot, $localSnapshot);
    }

  	$this->logSection($this->getName(), 'Loading snapshot into database');

    if (!file_exists($localSnapshot))
  	{
  		throw new LogicException('"' . $localSnapshot . '" does not exist.');
  	}

  	$this->logSection($this->getName(), 'Dropping all existing tables');

    //The below command comes from: http://www.thingy-ma-jig.co.uk/blog/10-10-2006/mysql-drop-all-tables
  	$this->getFilesystem()->execute(sprintf(
      'echo "SET FOREIGN_KEY_CHECKS = 0;" `mysqldump -u %s -p%s -h %s --add-drop-table --no-data %s | grep "\(^DROP\)\|\(DROP VIEW\)"` | mysql -u %s -p%s -h %s %s',
  	  $dbSettings['username'], $dbSettings['password'], $dbSettings['host'],$dbSettings['dbname'],
      $dbSettings['username'], $dbSettings['password'], $dbSettings['host'],$dbSettings['dbname']
  	));

  	$this->logSection($this->getName(), 'Loading snapshot');
  	$this->getFilesystem()->execute(sprintf(
      'mysql -u %s -p%s -h %s %s < %s',
  	  $dbSettings['username'], $dbSettings['password'], $dbSettings['host'],$dbSettings['dbname'], $localSnapshot
  	));

    if($options['and-migrate'])
    {
      $this->runTask('doctrine:migrate', array(), array(
        'application' => sfConfig::get('sf_app')
      ));
    }
  }


  protected function downloadLatestSnapshot($server, $remoteSnapshot, $localSnapshot)
  {
    $this->logSection($this->getName(), 'Downloading latest snapshot from ' . $server);

    $this->getFilesystem()->mkdirs(dirname($localSnapshot), 0755);

    $this->runTask('fs:copy', array(
      'direction' => 'from',
      'server' => $server,
      'source' => $remoteSnapshot,
      'dest' => $localSnapshot
    ), array(
      'method' => 'rsync',
      'rsync-options' => '\'--copy-links --progress\'',
    ));
  }
}