<?php
class wfProjectConfiguration extends sfProjectConfiguration
{
  public static function isOnProductionServer()
  {
    return php_uname('n') == sfConfig::get('app_production_hostname');
  }

  /**
   * @return string Host name as determined by the root directory of the project
   */
  public static function getHost()
  {
    if (!self::isOnProductionServer())
    {
      return 'local';
    }

    $rootDir = sfConfig::get('sf_root_dir');
    $hostDirs = sfConfig::get('app_production_host_dirs');

    if (!$rootDir || !$hostDirs)
    {
      throw new RuntimeException('ProjectConfiguration::getHost() - required variables not found.');
    }

    foreach($hostDirs as $host => $dir)
    {
      if($rootDir == $dir)
      {
        return $host;
      }
    }

    throw new RuntimeException('ProjectConfiguration::getHost() - rootDir does not match any host dirs.');
  }
}
