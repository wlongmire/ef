<?php
ini_set('memory_limit', -1);

if (preg_match('/(bot|spider|crawler)/i', $_SERVER['HTTP_USER_AGENT']))
{
  header("HTTP/1.1 503 Service Temporarily Unavailable");
  header("Status: 503 Service Temporarily Unavailable");
  die(1);
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
