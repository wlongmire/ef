<?php

if ($_GET['apc'] == 'clear')
{
  apc_clear_cache();
  apc_clear_cache('user');
  apc_clear_cache('opcode');
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration)->dispatch();