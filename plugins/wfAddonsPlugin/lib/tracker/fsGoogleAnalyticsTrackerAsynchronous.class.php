<?php

/* fsGoogleAnalyticsTrackerAsynchronous
 * 
 * REQUIRES sfGoogleAnalyticsPlugin
 *
 * This class allows us to put the analytics JS at the bottom of head.
 *
 * To use, put this in app.yml:
 *
  sf_google_analytics_plugin:
    enabled:      on
    classes:
      fsAsynchronous: 'fsGoogleAnalyticsTrackerAsynchronous'
    profile_id:   ANALYTICS ID GOES HERE
    tracker:      fsAsynchronous
    insertion:    head
 *
 * And don't forget to add the filter in filters.yml
 *
   sf_google_analytics_plugin:
     class: sfGoogleAnalyticsFilter
 *
 */

class fsGoogleAnalyticsTrackerAsynchronous extends sfGoogleAnalyticsTrackerAsynchronous
{
  protected function doInserthead(sfResponse $response, $content)
  {
    $old = $response->getContent();
    $new = str_ireplace('</head>', "\n".$content."\n</head>", $old);

    if ($old == $new)
    {
      $new .= $content;
    }

    $response->setContent($new);
  }

  public function isEnabled()
  {
    return parent::isEnabled() && fsTools::isProductionHost();
  }
}