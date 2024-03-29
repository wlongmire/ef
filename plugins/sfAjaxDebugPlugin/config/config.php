<?php
/* 
 * Copyright (c) 2008 Andreas Ferber <af+symfony@chaos-agency.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

/**
 * Configuration for sfAjaxDebugPlugin
 *
 * @package    sfAjaxDebugPlugin
 * @author     Andreas Ferber <af+symfony@chaos-agency.de>
 * @version    SVN: $Id: config.php 10257 2008-07-13 16:05:20Z aferber $
 */

if (sfConfig::get('sf_web_debug') && sfConfig::get('sf_ajax_debug'))
{
  if (!in_array('sfAjaxDebug', sfConfig::get('sf_enabled_modules', array())))
  {
    $this->dispatcher->notify(new sfEvent($this, 'application.log', array('sfAjaxDebug module not enabled', 'priority' => sfLogger::DEBUG)));
    sfConfig::set('sf_ajax_debug', false);
  }
  elseif (sfConfig::get('sf_ajax_debug_routes'))
  {
    $this->dispatcher->connect('routing.load_configuration', array('sfAjaxDebugRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}