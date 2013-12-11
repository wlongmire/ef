<?php

/**
 * Migrates to the latest version if necessary
 */
class fsMigrateDoctrineTask extends fsBaseTask
{
  protected function configure()
  {
    $this->namespace = 'fs';
    $this->name = 'migrate-doctrine';
    $this->briefDescription = 'Migrates database to current/specified version';
    $this->detailedDescription = 'The [fs:migrate-doctrine|INFO] task migrates database to current/specified version iff necessary.';

    $this->addArgument('version', sfCommandArgument::OPTIONAL, 'The version to migrate to');
    
    $this->addOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application to run the task under', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'Env', 'prod');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->runTask('doctrine:migrate', array(), array(
      'application' => $arguments['application'],
      'env' => 'prod'
    ));
  }
}