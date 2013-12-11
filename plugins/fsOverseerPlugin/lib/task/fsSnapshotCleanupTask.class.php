<?php
class fsSnapshotCleanupTask extends fsBaseTask
{
  protected function configure()
  {
    $this->namespace            = 'fs';
    $this->name                 = 'snapshot-cleanup';
    $this->briefDescription     = 'Removes old snapshots';

    $this->addOption('num', null, sfCommandOption::PARAMETER_REQUIRED, 'How many snapshots to keep for each database', 14);
  }


  protected function execute($arguments = array(), $options = array())
  {
    list($server, $properties) = $this->getThisServerProperties(array('snapshot_path'));

    $path = $properties['snapshot_path'] . '/' . $server;

    $dirs = sfFinder::type('directory')->maxdepth(0)->sort_by_name()->in($path);
    foreach ($dirs as $dir)
    {
      $snapshots = sfFinder::type('file')->maxdepth(0)->name('*.sql')->sort_by_name()->in($dir);
      while(count($snapshots) > $options['num'])
      {
        $delete = array_shift($snapshots);
        $this->getFilesystem()->remove($delete);
      }
    }
  }
}