<?php
class wfApcClearCacheTask extends wfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace            = 'wf';
    $this->name                 = 'apc-clear';
    $this->briefDescription     = 'Clears the APC cache for apache from the command line.';

    $this->addArgument('url', sfCommandArgument::REQUIRED, 'The web address to clear the cache at');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->logSection($this->getName(), 'Clearing APC cache...');

    $url = (strpos($arguments['url'], 0, 4) == 'http' ? '' : 'http://') .
           $arguments['url'] .
           (substr($arguments['url'], -1) == '/' ? '' : '/') .
           'apc_clear/' . $this->getSyncPassword();

    $result = json_decode(file_get_contents($url), true);

    if (isset($result['success']) && $result['success'])
    {
      $this->logSection($this->getName(), 'APC cache cleared.');
    }
    else
    {
      $this->logSection($this->getName(), 'Something went wrong.', null, 'ERROR');
    }
  }

  protected function getSyncPassword()
  {
    $settings = parse_ini_file(sfConfig::get('sf_root_dir') . "/config/properties.ini", true);
    if ($settings === false)
    {
      throw new RuntimeException("Cannot find config/properties.ini");
    }

    if (!isset($settings['sync']) || !isset($settings['sync']['password']))
    {
      throw new RuntimeException('Sync section not configured or missing password in properties.ini');
    }

    return $settings['sync']['password'];
  }
}