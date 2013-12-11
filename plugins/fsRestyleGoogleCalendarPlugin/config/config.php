<?php

if (sfConfig::get('app_fs_restyle_google_calendar_plugin_routes_register', true))
{
  if (in_array('fsRestyleGoogleCalendar', sfConfig::get('sf_enabled_modules', array())))
  {
    $this->dispatcher->connect('routing.load_configuration', array('fsRestyleGoogleCalendarRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}