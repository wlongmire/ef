<?php

class fsCacheFilter extends sfCacheFilter
{
  /*
   * Overridden to always call setCacheExpiration and setCacheValidation
   */
  public function executeBeforeRendering()
  {
    // cache only 200 HTTP status
    if (200 != $this->response->getStatusCode())
    {
      return;
    }

    $uri = $this->routing->getCurrentInternalUri();

    // save page in cache
    if (isset($this->cache[$uri]) && false === $this->cache[$uri])
    {
      // set Vary headers
      foreach ($this->cacheManager->getVary($uri, 'page') as $vary)
      {
        $this->response->addVaryHttpHeader($vary);
      }

      $this->cacheManager->setPageCache($uri);
    }

    $this->setCacheExpiration($uri);
    $this->setCacheValidation($uri);
    $this->checkCacheValidation();
    
    //TODO: VARY header should be set here somewhere, but IE7 is retarded so I'm not doing that now
    //see http://blogs.msdn.com/ieinternals/archive/2009/06/17/Vary-Header-Prevents-Caching-in-IE.aspx
    //and http://support.microsoft.com/kb/824847
  }

  /**
   * Override setCacheExpiration to always make sure no client side caching is done of any pages.
   * Also, set cache to be disabled for XMLHttpRequests in IE
   * @see sfCacheFilter::setCacheExperation
   * @param $uri
   */
  public function setCacheExpiration($uri)
  {
    if (!$this->response->hasHttpHeader('Expires'))
    {
      if ($this->request->isFromIE() && $this->request->isXmlHttpRequest())
      {
         /* Disable caching of AJAX requests for IE:
         *  http://stackoverflow.com/questions/244918/internet-explorer-7-ajax-links-only-load-once/244923#244923*/
        $this->response->setHttpHeader('Pragma', 'no-cache');
        $this->response->setHttpHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
      }
      $this->response->setHttpHeader('Expires', '-1');
      $this->response->addCacheControlHttpHeader('max-age', 0);
      $this->response->addCacheControlHttpHeader('must-revalidate');
    }
  }
  
  public function setCacheValidation($uri)
  {
    if (!$this->response->hasHttpHeader('Last-Modified'))
    {
      $this->response->setHttpHeader('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT', false);
    }

    if (sfConfig::get('sf_etag'))
    {
      $etag = '"'.md5($this->response->getContent()).'"';
      $this->response->setHttpHeader('ETag', $etag);
    }
  }
}