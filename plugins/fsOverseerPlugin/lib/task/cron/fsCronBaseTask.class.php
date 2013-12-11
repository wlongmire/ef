<?php
abstract class fsCronBaseTask extends fsBaseTask
{
  protected static $cronNamespace = 'cron';

  protected function configure()
  {
    $this->namespace = static::$cronNamespace;

    $this->addOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application to run the task under', 'frontend');
  }

  protected function initState($orderBy = null)
  {
  	if ($this->databaseManager == null)
  	{
      $this->databaseManager = new sfDatabaseManager($this->configuration);
  	}
    $this->tasks = fsCronTaskTable::getTasks($orderBy);
  }
}