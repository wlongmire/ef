<?php

class fsSyncTask extends fsBaseTask
{
  protected $outputBuffer, $errorBuffer;

  protected function configure()
  {
    $this->namespace        = 'fs';
    $this->name             = 'sync';
    $this->briefDescription = 'Sync content from one project to another';

    $this->addOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application to run the task under', 'frontend');

    $this->addArgument('server', sfCommandArgument::REQUIRED, 'A server name as listed in properties.ini (examples: staging, production)');

    $this->addOption('sync-to-production', null, sfCommandOption::PARAMETER_NONE,
      'This option is required when syncing to the production server.');
  }

  protected function execute($arguments = array(), $options = array())
  {
    if ($this->isProduction() && !$options['sync-to-production'])
    {
      throw new RuntimeException(
        'Your are trying to load sync content to production. Use the --sync-to-production flag if you are sure you want to do this.');
    }

    $syncDirs = sfConfig::get('app_fs_overseer_plugin_syncable_dirs', sfConfig::get('app_syncable_dirs'));

    if (!$syncDirs)
    {
      throw new RuntimeException('You do not have any syncable directories defined.');
    }

    foreach ($syncDirs as $syncDir)
    {
      $this->runTask('fs:copy', array(
        'direction' => 'from',
        'server' => $arguments['server'],
        'source' => $syncDir . '/',
        'dest' => $this->configuration->getRootDir() . '/' . $syncDir
      ), array(
        'rsync-options' => '"-azvCcI --no-t --force --delete --progress"'
      ));
    }
  }
}