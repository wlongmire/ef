<?php

class wfBrowser extends sfBrowser
{
  protected $forceSslOnNextRequest = false;

  /**
   * Force the next request to use SSL
   *
   * @return wfBrowser
   */
  public function sslNextRequest()
  {
    $this->forceSslOnNextRequest = true;
    return $this;
  }

  /**
   * Overwritten to allow us to force SSL
   *
   * @param  string $uri  The URI to fix
   * @return string The fixed uri
   */
  public function fixUri($uri)
  {
    $uri = parent::fixUri($uri);

    if ($this->forceSslOnNextRequest)
    {
      $this->defaultServerArray['HTTPS'] = 'on';
    }

    return $uri;
  }

  /**
   * Overwritten to clear forceSslOnNexRequest after a request is made
   *
   * @param string $uri          The URI to fetch
   * @param string $method       The request method
   * @param array  $parameters   The Request parameters
   * @param bool   $changeStack  Change the browser history stack?
   *
   * @return wfBrowser
   */
  public function call($uri, $method = 'get', $parameters = array(), $changeStack = true)
  {
    parent::call($uri, $method, $parameters, $changeStack);
    if ($this->forceSslOnNextRequest)
    {
      unset($this->defaultServerArray['HTTPS']);
      $this->forceSslOnNextRequest = false;
    }
    return $this;
  }
}