<?php
/*
 * Copyright (c) Andreas Ferber <af+symfony@chaos-agency.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

/**
 * sfAjaxDebugRouting registers routes for sfAjaxDebugPlugin.
 *
 * @package    sfAjaxDebugPlugin
 * @author     Andreas Ferber <af+symfony@chaos-agency.de>
 * @version    SVN: $Id: sfAjaxDebugRouting.class.php 10257 2008-07-13 16:05:20Z aferber $
 */
class sfAjaxDebugRouting
{

  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // prepend our routes
    $r->prependRoute('sf_ajax_debug_get', new sfRoute('/sfAjaxDebug/:token', array('module' => 'sfAjaxDebug', 'action' => 'get')));
    $r->prependRoute('sf_ajax_debug_css', new sfRoute('/sfAjaxDebug/main.css', array('module' => 'sfAjaxDebug', 'action' => 'css')));
    $r->prependRoute('sf_ajax_debug_js', new sfRoute('/sfAjaxDebug/main.js', array('module' => 'sfAjaxDebug', 'action' => 'js')));
  }
}