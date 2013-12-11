<?php
require_once dirname(__FILE__) . '/../../../../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';

class wfCoreAutoload extends sfCoreAutoload
{
  /**
   * Retrieves the singleton instance of this class.
   *
   * @return wfCoreAutoload A wfCoreAutoload implementation instance.
   */
  static public function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new wfCoreAutoload();
    }

    return self::$instance;
  }
  
  public function getClassPath($class)
  {
    $class = strtolower($class);

    if (!isset($this->classes[$class]))
    {
      return null;
    }

    return $this->classes[$class][0] == '/' ? $this->classes[$class] : $this->baseDir.'/'.$this->classes[$class]; //if it's an absolute path, return it
  }

  public function setClassPath($class, $path)
  {
    $class = strtolower($class);
    $this->classes[$class] = $path;
  }
}