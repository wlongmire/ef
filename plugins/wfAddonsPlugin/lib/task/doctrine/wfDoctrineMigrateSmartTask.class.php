<?php

/**
 * Migrates to the latest version if necessary
 */
class wfDoctrineMigrateSmartTask extends wfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace = 'doctrine';
    $this->name = 'migrate-smart';
    $this->aliases = array('doctrine-migrate-smart');
    $this->briefDescription = 'Migrates database to current/specified version';
    $this->detailedDescription = 'The [doctrine:migrate-smart|INFO] task migrates database to current/specified version iff necessary.';

    $this->addArguments(array(new sfCommandArgument('version', sfCommandArgument::OPTIONAL, 'The version to migrate to', null)));
    $this->addOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application to run the task under', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment to run the task under', 'prod');
    $this->addOption('skip-build-model', null, sfCommandOption::PARAMETER_NONE, 'Skip rebuilding the model after a successful migration');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->loadDatabaseManager();
    sfContext::createInstance($this->configuration);

    $config = $this->getCliConfig();
    $migration = new Doctrine_Migration($config['migrations_path']);
    
    if ($arguments['version'] == false)
    {
      $arguments['version'] = $migration->getLatestVersion();
    }
    if ($migration->getCurrentVersion() < $arguments['version'])
    {
      $version = $migration->migrate($arguments['version']);
      $this->logSection($this->getName(), 'Successfully migrated to version #' . $version);

      if ($options['skip-build-model'])
      {
        $this->logSection($this->getName(), 'Skipping build-model');
      }
      else
      {
        $this->logSection($this->getName(), 'Rebuilding model after migrations');
        $this->runTask('doctrine:build-model');
      }
    }
    else
    {
      $this->logSection($this->getName(), sprintf('Already at version #%s, skipping migrate task and model rebuild.', $arguments['version']));
    }
  }
}