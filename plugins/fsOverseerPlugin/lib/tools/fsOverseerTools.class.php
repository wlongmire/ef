<?php
class fsOverseerTools
{
  public static function getHostname()
  {
    $hostname = php_uname('n');
    if (!$hostname)
    {
      throw new UnexpectedValueException('Unable to get hostname using php_uname()');
    }
    return $hostname;
  }

  public static function getProductionHostname()
  {
    $appKey = 'app_fs_overseer_plugin_production_hostname';
    $hostname = sfConfig::get($appKey, null);
    if ($hostname === null)
    {
      throw new RuntimeException('Production hostname is not set. Please set ' . $appKey . ' in app.yml');
    }
    return $hostname;
  }

  public static function getProductionDir()
  {
    $appKey = 'app_fs_overseer_plugin_production_dir';
    $dir = sfConfig::get($appKey, null);
    if ($dir === null)
    {
      throw new RuntimeException('Production directory is not set. Please set ' . $appKey . ' in app.yml');
    }
    return $dir;
  }

  public static function isProductionServer()
  {
    return static::getHostname() == static::getProductionHostname();
  }

  public static function isProductionHost()
  {
    return static::isProductionServer() && sfConfig::get('sf_root_dir') == static::getProductionDir();
  }

  public static function getLockDir()
  {
    return sfConfig::get('sf_root_dir') . '/' . sfConfig::get('app_fs_overseer_plugin_lock_dir', 'data/lock');
  }

  /**
   * Creates a lockfile and acquires an exclusive lock on it.
   *
   * @param string $filename The name of the lockfile.
   * @params boolean $perApp True (the default) if there are separate lock files for each app, false if there's one for the whole server.
   * @return mixed Returns the lockfile, or FALSE if a lock could not be acquired.
   */
  public static function getExclusiveLock($name, $perApp = true)
  {
    $filename = fsOverseerTools::getLockDir() . '/' . ($perApp ? sfConfig::get('sf_app','noapp').'_' : '') . $name;
    if (substr($filename, -4) != '.lck' && substr($filename, -5) != '.lock')
    {
      $filename .= '.lck';
    }
    $lockFile = fopen($filename, 'w+');
    //@chmod($filename, 0666); // don't really care if this works, but it probably helps
    if (!flock($lockFile, LOCK_EX|LOCK_NB))
    {
      fclose($lockFile);
      return FALSE;
    }
    return $lockFile;
  }

  /**
   * Frees an exclusive lock.
   *
   * @param resource $lockFile
   */
  public static function freeExclusiveLock($lockFile)
  {
    if ($lockFile)
    {
      flock($lockFile, LOCK_UN);
      fclose($lockFile);
    }
  }
}
