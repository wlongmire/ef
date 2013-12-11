<?php
class fsPreDeployTask extends fsBaseTask
{
  protected function configure()
  {
    $this->namespace = 'fs';
    $this->name = 'pre-deploy';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->getName(), 'Taking site down');
    $this->runTask('fs:site', array('action' => 'down'));
    $this->logSection($this->getName(), 'Taking database snapshot');
    $this->runTask('fs:snapshot', array('action' => 'save'), array('all-apps'));
  }
}