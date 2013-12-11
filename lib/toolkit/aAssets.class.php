<?php

/**
 * Description of aAssets
 *
 * @author jeremy
 */
class aAssets extends BaseaAssets
{
  public static function getGroupFilename($files)  
  {
    return md5(implode('', $files) . static::getAssetVersion(). Site::current()->getGlobalLessPath());    
  }
  
  public static function getFileName($file)
  {
    return str_replace('/', '-', ltrim($file, '/')) . '.' . static::getAssetVersion() . '.' . strtolower(Site::current()->name) . '.less.css';
  }

  static public function compileLessIfNeeded($path, $compiled, $options = array())
  {
	  $always = sfConfig::get('app_a_less_compile_always', false);
    $checkIfModified = isset($options['checkIfModified']) && $options['checkIfModified'];
    if (!sfConfig::get('app_a_minify'))
    {
      $checkIfModified = true;
    }
    if ($always || ((!file_exists($compiled)) || ($checkIfModified && (filemtime($compiled) < filemtime($path)))))
    {
      if (!isset(aAssets::$lessc))
      {
        aAssets::$lessc = new my_lessc();
      }
      aAssets::$lessc->importDir = dirname($path) . '/';
      file_put_contents($compiled, aAssets::$lessc->parse(file_get_contents(Site::current()->getGlobalLessPath()) . file_get_contents($path)));
    }
  }
  
  static public function getAssetVersion()
  {
    $path = aFiles::getUploadFolder(array('asset-cache')) . '/asset-version';
    $filemtime = file_exists($path) ? filemtime($path) : false;
    if ($filemtime === false) //it doesn't exist
    {
      touch($path);
      $filemtime = filemtime($path);
    }
    return $filemtime . (Site::current() ? Site::current()->updated_at : '');
  }
}
