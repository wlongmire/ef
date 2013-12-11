<?php 
class wfActions extends sfActions
{
  /**
   * @param DateTime $date
   * @return An array with the month/day/year keys of $date
   */
  protected function dateArray(DateTime $date)
  {
    return array('month' => $date->format('m'), 'day' => $date->format('d'), 'year' => $date->format('Y'));
  }

  
  /**
   * If the request is not an XML HTTP request, forward to the given route. Arguments are same as generateUrl()
   * @param string $route
   * @param array $params
   * @param boolean $absolute
   */
  protected function redirectIfNotXmlHttp($route, $params = array(), $absolute = false)
  {
    if ( !$this->getRequest()->isXmlHttpRequest() )
    {
      $url = $this->generateUrl($route, $params, $absolute);
      sfContext::getInstance()->getLogger()->warning(sprintf('Route "%s" requires XMLHTTP. Redirecting to "%s"',
        $this->getRequest()->getPathInfo(), $url));
      $this->redirect($url);
    }
  }

  /**
   * If the request is not an XML HTTP request OR $condition is not met, forward to the give route. Arguments are same as generateUrl()
   * @param mixed $condition If $condition evaluates to false, this will forward to the provided url
   * @param string $route
   * @param array $params
   * @param boolean $absolute
   */
  protected function redirectIfNotXmlHttpAnd($condition, $route, $params = array(), $absolute = false)
  {
    if (!$condition)
    {
      $this->redirect($this->generateUrl($route, $params, $absolute));
    }
    $this->redirectIfNotXmlHttp($route, $params, $absolute);
  }

  /*
   * If the request is not an XML HTTP request, forward to a 404
   */
  protected function requireXmlHttp()
  {
    if ( !$this->getRequest()->isXmlHttpRequest() )
    {
      $message = sprintf('Route "%s" requires XML HTTP.', $this->getRequest()->getPathInfo());
      $this->getContext()->getLogger()->err($message);
      $this->forward404($message);
    }
  }

  /**
   * Forward to a 404 if the request is not XML HTTP or $condition is not true
   * @param boolean $condition
   * @param string $message Message to log if $condition fails (optional)
   */
  protected function requireXmlHttpAnd($condition, $message = null)
  {
    if (!$condition)
    {
      $this->forward404($message);
    }
    $this->requireXmlHttp();
  }

  /**
   * Sets the response header to be JSON
   */
  protected function isJSON()
  {
    $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');
  }

  /**
   * Sets the response header and returns a JSON-encoded value
   * @param mixed $data Anything but a resource
   * @param boolean $preventAssetInjection True if you want to disable asset injection (this is the default)
   * @return string JSON-encoded array
   */
  protected function renderJSON($data, $preventAssetInjection = true)
  {
    sfConfig::set('app_enable_asset_injection', !$preventAssetInjection);
    $this->isJSON();
    return $this->renderText(json_encode($data));
  }
  
  protected function renderJSONP($data, $preventAssetInjection = true)
  {
    sfConfig::set('app_enable_asset_injection', !$preventAssetInjection);
    $this->isJSON();
    return $this->renderText(sprintf('%s(%s);', $this->request['callback'], json_encode($data)));
  }

  /**
   * Checks the current and max memory usage of the script.
   * Logs a warning if memory usage is above a certain threshold of maximum.
   */
  protected function checkMemoryStatus($forceLog = false)
  {
    $maxMemoryUsage = $this->getBytes(ini_get('memory_limit'));
    $currentMemoryUsage = memory_get_usage();
    $peakMemoryUsage = memory_get_peak_usage();

    if ($forceLog || $currentMemoryUsage / $maxMemoryUsage > .7)
    {
      $this->getContext()->getLogger()->warning(sprintf('Current memory usage is "%s" of "%s"',
                                                   $currentMemoryUsage, $maxMemoryUsage));
    }
    if ($forceLog || $peakMemoryUsage / $maxMemoryUsage > .7)
    {
      $this->getContext()->getLogger()->warning(sprintf('Current memory usage is "%s" of "%s"',
                                                   $this->getHumanReadable($peakMemoryUsage), $this->getHumanReadable($maxMemoryUsage)));
    }
  }

  /**
   * @param string $size A byte size with a unit as a suffix (G, M, or K) as would be used in php ini
   * @return integer the size in bytes
   */
  protected function getBytes($size)
  {
    $size = trim($size);
    $last = strtolower($size[strlen($size)-1]);
    switch($last)
    {
      case 'g':
          $size *= 1024;
      case 'm':
          $size *= 1024;
      case 'k':
          $size *= 1024;
    }
    return $size;
  }

  /**
   * @param integer $bytes
   * @return string $bytes in a human readable format (unit deduced)
   */
  protected function getHumanReadable($bytes)
  {
    $unit = array('b', 'kb','mb','gb','tb','pb');
    $index = floor(log($bytes, 1024));
    return round($bytes/pow(1024,$index),2). ' ' . $unit[$index];
  }
}