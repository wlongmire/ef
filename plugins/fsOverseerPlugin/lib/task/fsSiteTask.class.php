<?php
class fsSiteTask extends fsBaseTask
{
  protected function configure()
  {
    $this->namespace            = 'fs';
    $this->name                 = 'site';
    $this->briefDescription     = 'Take the site down or bring it up';

    $this->addArgument('action', sfCommandArgument::REQUIRED, 'One of "up" or "down" to put the site up or down.');

    $this->addOption('message', null, sfCommandOption::PARAMETER_OPTIONAL, 'The message to put on the unavailable page.', null);
    $this->addOption('htaccess', null, sfCommandOption::PARAMETER_REQUIRED, 'The name of the htaccess file to use', null);
  }

  protected function execute($arguments = array(), $options = array())
  {
    $downTemplateSource = sfConfig::get('sf_config_dir') . '/unavailable.php';
    $downTemplateDest = sfConfig::get('sf_web_dir') . '/unavailable.php';
    $htAccessDest = sfConfig::get('sf_web_dir') . '/.htaccess';

    $lockFile = sfConfig::get('sf_data_dir') . '/site_is_down.lock';

    if ($arguments['action'] == 'down')
    {
      if (file_exists($lockFile))
      {
        throw new RuntimeException('Looks like the site is already down. That is not correct, please delete ' . $lockFile);
      }

      if (!file_exists($downTemplateSource))
      {
        $this->logSection($this->getName(), 'Cannot find config/unavailable.php file, getting file from plugin');
        $downTemplateSource = sfConfig::get('sf_plugins_dir') . '/fsOverseerPlugin/config/unavailable.php';
      }

      ob_start();
      $message = $options['message'];
      echo '<?php header($_SERVER[\'SERVER_PROTOCOL\'] . \' 503 Service Unavailable\') ?>' . "\n"; // needs to be reinserted because require evaluates it and removes it
      require $downTemplateSource;
      file_put_contents($downTemplateDest, ob_get_clean());

      $htAccessSource = sfConfig::get('sf_web_dir') . '/' . ($options['htaccess'] ?: '.htaccess.down');
      if (!file_exists($htAccessSource))
      {
        $this->logSection($this->getName(), 'Cannot find .htaccess file, getting file from plugin');
        $htAccessSource = sfConfig::get('sf_plugins_dir') . '/fsOverseerPlugin/config/htaccess.down';
      }
      $this->getFilesystem()->copy($htAccessDest, $htAccessDest . '.bak', array('override' => true));
      $this->getFilesystem()->copy($htAccessSource, $htAccessDest, array('override' => true));

      $this->getFilesystem()->touch($lockFile);

      $this->logSection($this->getName(), 'Site is down.');
    }


    else if ($arguments['action'] == 'up')
    {
      $htAccessSource = sfConfig::get('sf_web_dir') . '/' . ($options['htaccess'] ?: '.htaccess.up');
      if (!file_exists($htAccessSource))
      {
        $this->logSection($this->getName(), 'Cannot find .htaccess file, looking for .htaccess.bak');
        $htAccessSource = sfConfig::get('sf_web_dir') . '/.htaccess.bak';
        if (!file_exists($htAccessSource))
        {
          throw new RuntimeException('Cannot find source .htaccess file');
        }
      }
      $this->getFilesystem()->copy($htAccessSource, $htAccessDest, array('override' => true));
      $this->getFilesystem()->remove($downTemplateDest); //remove the unavailable file
      $this->getFilesystem()->remove($lockFile); //remove the lockfile that says site is down

      $this->logSection($this->getName(), 'Site is up.');
    }

    
    else
    {
      throw new InvalidArgumentException('"Action" must be one of "up" or "down"');
    }
  }
}