<?php
class fsPostDeployTask extends fsBaseTask
{
  protected function configure()
  {
    $this->namespace = 'fs';
    $this->name = 'post-deploy';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->getName(), 'Migrating');
    $this->runTask('fs:migrate-doctrine', array(), array('all-apps'));
    $this->logSection($this->getName(), 'Clearing cache');
    $this->runTask('cache:clear');
    $this->logSection($this->getName(), 'Bringing site up');
    $this->runTask('fs:site', array('action' => 'up'));

    // clear apc cache?
    //$this->logSection($this->getName(), 'Clearing APC cache');
  }
}