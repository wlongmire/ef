<?php
class fsTools
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

  public static function getApplications()
  {
    return array_map(function($directory) { return basename($directory); },
                     sfFinder::type('dir')->maxdepth(0)->in(sfConfig::get('sf_apps_dir')));
  }

  public static function applicationExists($application)
  {
    return in_array($application, static::getApplications());
  }

  /**
   * Get the database settings for the current database or for a given database
   *
   * @see sfSyncContentTools() in sfSyncContentPlugin
   *
   * @param string|null $dbName The name of the database to get. Defaults to the first defined database.
   * @param sfProjectConfiguration|null $configuration
   * @return array
   */
  public static function getDatabaseSettings($dbName = null, $configuration = null)
  {
    if ($configuration === null)
    {
      $configuration = sfContext::getInstance()->getConfiguration();
    }

    $dbManager = new sfDatabaseManager($configuration);
    $connectionNames = $dbManager->getNames();

    if ($dbName)
    {

      foreach($connectionNames as $connectionName)
      {
        $database = $dbManager->getDatabase($connectionName);
        $data = static::parseDsn($database->getParameter('dsn'));
        if ($data['dbname'] == $dbName)
        {
          $db = $database;
          break;
        }
      }

      if (!$db)
      {
        throw new sfException("No database connection has database $dbName.");
      }
    }
    else
    {
      $db = $dbManager->getDatabase($connectionNames[0]);
      $data = static::parseDsn($db->getParameter('dsn'));
    }

    $data['username'] = $db->getParameter('username');
    $data['password'] = $db->getParameter('password');

    return $data;
  }

  /**
   * Break down the database DSN string.
   *
   * @param string $dsn
   * @return array
   */
  public static function parseDsn($dsn)
  {
    if (!preg_match('/^mysql:(.*)\s*$/', $dsn, $matches))
    {
      throw new sfException("Cannot parse DSN $dsn");
    }
    $pairs = explode(';', $matches[1]);
    $data = array();
    foreach ($pairs as $pair)
    {
      list($key, $val) = explode('=', $pair);
      $data[$key] = $val;
    }

    return $data;
  }
}
