<?php

class fsOverseerPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('command.post_command', array('fsOverseerEvents',  'listenToCommandPostCommandEvent'));
  }
}
