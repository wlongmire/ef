<?php
use_stylesheet(sfConfig::get('app_fs_restyle_google_calendar_plugin_less_css'));

extract(array(
  'height' => 600, # i think this must be a number because it's included in the get params for the iframe url
  'width' => '100%',
  'showTitle' => false,
  'showTabs' => false,
  'showCalendars' => false,
  'showTimezone' => true,
  'weekStart' => 1,
  'timezone' => 'America/New_York',
  'bgcolor' => '#ffffff',
  'calendars' => sfConfig::get('app_fs_restyle_google_calendar_plugin_calendars', array()),
  'defaultColor' => '#29527A',
), EXTR_SKIP);

$params = array(
  'showTitle' => $showTitle ? 1 : 0,
  'showTabs' => $showTabs ? 1 : 0,
  'showCalendars' => $showCalendars ? 1 : 0,
  'showTz' => $showTimezone ? 1 : 0,
  'wkst' => $weekStart,
  'ctz' => $timezone,
  'height' => $height,
  'bgcolor' => $bgcolor,
);

$sources = '';
if ($calendars)
{
  foreach($calendars as $calendar)
  {
    if (is_array($calendar))
    {
      $sources .= '&src=' . urlencode($calendar['src']);
      $sources .= '&color=' . (isset($calendar['color']) ? urlencode($calendar['color']) : $defaultColor);
    }
    else
    {
      $sources .= '&src=' . urlencode($calendar);
    }
  }
}
?>

<?php if($calendars): ?>
  <iframe src="<?php echo url_for('fs_restyle_google_calendar_iframe', $params) . $sources ?>"
          style="border: 0" width="<?php echo $width ?>" height="<?php echo $height?>" frameborder="0" scrolling="no"></iframe>
<?php else: ?>
  <div class="notice">Calendar not configured.</div>
<?php endif ?>