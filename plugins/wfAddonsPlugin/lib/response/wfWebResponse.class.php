<?php

class wfWebResponse extends sfWebResponse
{
  protected $openGraphProperties = array(),
            $canonicalUrl = null,
            $rssUrl = null;

  /**
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public function getMeta($key, $default = null)
  {
    $key = strtolower($key);

    return isset($this->metas[$key]) ? $this->metas[$key] : $default;
  }

  /**
   * @param  string $name  Property name
   * @return string Normalized porperty name
   */
  protected function normalizeOpenGraphPropertyName($name)
  {
    return preg_replace('/\-(.)/e', "'-'.strtoupper('\\1')", strtr(strtolower($name), '_', '-'));
  }


  /**
   * @param string  $key
   * @param string  $value Property value (if null, remove the property)
   */
  public function setOpenGraphProperty($key, $value)
  {
    $key = $this->normalizeOpenGraphPropertyName($key);

    if (null === $value)
    {
      unset($this->openGraphProperties[$key]);
      return;
    }

    $this->openGraphProperties[$key] = $value;
  }

  /**
   * @param string $key
   * @return string
   */
  public function getOpenGraphProperty($key)
  {
    $key = $this->normalizeOpenGraphPropertyName($key);
    return isset($this->openGraphProperties[$key]) ? $this->openGraphProperties[$key] : null;
  }

  /**
   * Sets the canonical URL for a page
   * @see http://googlewebmastercentral.blogspot.com/2009/02/specify-your-canonical.html
   * @see wfWebResponse::getCanonicalUrl
   * @param string $url The canonical URL for the response
   */
  public function setCanonicalUrl($url)
  {
    $this->canonicalUrl = $url;
  }

  /**
  * Gets the canonical URL for a page
  * @see http://googlewebmastercentral.blogspot.com/2009/02/specify-your-canonical.html
  * @see wfWebResponse::setCanonicalUrl
  * @return string|null The canonical URL for the page, if set
  */
  public function getCanonicalUrl()
  {
    return $this->canonicalUrl;
  }

  /**
   * Sets the rss feed link
   * @param string $url The RSS URL
   */
  public function setRssUrl($url)
  {
    $this->rssUrl = $url;
  }

  /**
   * @return string The RSS URL
   */
  public function getRssUrl()
  {
    return $this->rssUrl;
  }


  /**
   * @return array
   */
  public function getOpenGraphProperties()
  {
    return $this->openGraphProperties;
  }

  /**
   * Copies all properties from a given wfWebResponse object to the current one.
   *
   * @param sfWebResponse $response  An sfWebResponse instance
   */
  public function copyProperties(sfWebResponse $response)
  {
    parent::copyProperties($response);
    if ($response instanceof wfWebResponse)
    {
      $this->openGraphProperties = $response->getOpenGraphProperties();
      $this->setCanonicalUrl($response->getCanonicalUrl());
    }
  }

  /**
   * Overwritten to notify response.filter_headers event before sending headers.
   */
  public function sendHttpHeaders()
  {
    if (!$this->options['send_http_headers'])
    {
      return;
    }
    $this->dispatcher->notify(new sfEvent($this, 'response.filter_headers')); // this probably shouldn't be called filter_headers as it doesn't filter anything
    parent::sendHttpHeaders();
  }

  /**
   * A response is missing a meta description if all of the following are true:
   * * It has no meta description (duh)
   * * Accessed via GET
   * * Not accessed via XMLHTTP
   * * Not a secure page
   * * Not a noindex page
   */
  public function isMissingMetaDescription()
  {
    $context = sfContext::getInstance();
    return !$this->getMeta('description') &&
              $context->getRequest()->isMethod('get') &&
              !$context->getRequest()->isXmlHttpRequest() &&
              strpos($this->getMeta('robots'), 'noindex') === false &&
              !$context->getController()->getActionStack()->getLastEntry()->getActionInstance()->isSecure();
  }
}