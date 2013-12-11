<?php

class fsRestyleGoogleCalendarRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    // preprend our routes
    $r->prependRoute('fs_restyle_google_calendar_iframe',
      new sfRoute('/fs_restyle_google_calendar/iframe', array('module' => 'fsRestyleGoogleCalendar', 'action' => 'iframe'))
    );
    $r->prependRoute('fs_restyle_google_calendar_js',
      new sfRoute('/fs_restyle_google_calendar/js', array('module' => 'fsRestyleGoogleCalendar', 'action' => 'js'))
    );
  }
}