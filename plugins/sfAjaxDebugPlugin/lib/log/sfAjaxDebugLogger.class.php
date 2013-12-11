<?php
/*
 * Copyright (c) 2008 Andreas Ferber <af+symfony@chaos-agency.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

/**
 * sfAjaxDebugLogger stores the web debug toolbar for XMLHttpRequests
 * into a cache for later retrieval.
 *
 * @package    sfAjaxDebugPlugin
 * @author     Andreas Ferber <af+symfony@chaos-agency.de>
 * @version    SVN: $Id: sfAjaxDebugLogger.class.php 10257 2008-07-13 16:05:20Z aferber $
 */
class sfAjaxDebugLogger extends sfWebDebugLogger
{

  protected $token = null;

  protected static $cache = null;

  /**
   * Initializes this logger.
   *
   * @param   sfEventDispatcher $dispatcher   A sfEventDispatcher instance
   * @param   array             $options      An array of options.
   *
   * @return  boolean  true, if initialization completes successfully, otherwise false
   */
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    $this->token = wfToolkit::uniqueId();
    $dispatcher->connect('response.filter_headers', array($this, 'filterResponseHeaders'));
    return parent::initialize($dispatcher, $options);
  }

  /**
   * Listens to the response.filter_headers event.
   *
   * @param   sfEvent $event   The sfEvent instance
   * @param   array   $headers The response headers
   *
   * @return  array   The filtered response headers
   */
  public function filterResponseHeaders(sfEvent $event)
  {
    if (!sfConfig::get('sf_web_debug') || !sfConfig::get('app_sf_ajax_debug_enable', true))
    {
      return;
    }

    if ($this->context->has('request') && $this->context->has('response') && $this->context->getRequest()->isXmlHttpRequest())
    {
      $response = $event->getSubject();
      $response->setHttpHeader('X-sfAjaxDebug-Token', $this->token);
      $firstEntry = $this->context->getActionStack()->getFirstEntry();
      if ($firstEntry)
      {
        $response->setHttpHeader('X-sfAjaxDebug-Module', $firstEntry->getModuleName());
        $response->setHttpHeader('X-sfAjaxDebug-Action', $firstEntry->getActionName());
      }
    }
  }

  /**
   * Listens to the response.filter_content event.
   *
   * @param   sfEvent $event   The sfEvent instance
   * @param   string  $content The response content
   *
   * @return  string  The filtered response content
   */
  public function filterResponseContent(sfEvent $event, $content)
  {
    $content = parent::filterResponseContent($event, $content);

    if (!sfConfig::get('sf_web_debug') || !sfConfig::get('app_sf_ajax_debug_enable', true) || $this->webDebug === null)
    {
      return $content;
    }

    if ($this->context->has('request') && $this->context->has('response') && $this->context->getRequest()->isXmlHttpRequest())
    {
      self::getCache()->set($this->token, $this->webDebug->asHtml());
      return $content;
    }

    // taken from sfWebDebugLogger
    $response = $event->getSubject();
    if (
      !$this->context->has('request')
      ||
      !$this->context->has('response')
      ||
      !$this->context->has('controller')
      ||
      strpos($response->getContentType(), 'html') === false
      ||
      '3' == substr($response->getStatusCode(), 0, 1)
      ||
      $this->context->getController()->getRenderMode() != sfView::RENDER_CLIENT
      ||
      $response->isHeaderOnly()
    )
    {
      return $content;
    }

    $scriptUrl = $this->context->getController()->genUrl('sfAjaxDebug/js');
    $assets = sprintf('<script type="text/javascript" src="%s"></script>', $scriptUrl);
    $cssUrl = $this->context->getController()->genUrl('sfAjaxDebug/css');
    $assets .= sprintf('<link rel="stylesheet" type="text/css" media="screen" href="%s" />', $cssUrl);
    $content = str_ireplace('</head>', $assets.'</head>', $content);

    return $content;
  }

  /**
   * Get the cache for stored web debug toolbars
   *
   * @return  sfCache  The sfCache instance used for storing the data
   */
  public static function getCache()
  {
    if (!self::$cache)
    {
      self::$cache = new sfFileCache(array(
          'cache_dir' => sfConfig::get('sf_app_cache_dir').'/ajax_debug',
          'lifetime' => 300
      ));
    }
    return self::$cache;
  }
}