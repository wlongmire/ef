<?php
class BasefsRestyleGoogleCalendarActions extends sfActions
{
  public function executeIframe(sfWebRequest $request)
  {
    // http://code.google.com/p/restylegc/

    $this->getContext()->getConfiguration()->loadHelpers(array('Asset'));

    $stylesheet = aTools::getLessPath(stylesheet_path(sfConfig::get('app_fs_restyle_google_calendar_plugin_less_css')), false);
    $javascript = $this->generateUrl('fs_restyle_google_calendar_js');

    // URL for the calendar
    $url = "";
    if(count($_GET) > 0) {
      $url = "https://www.google.com/calendar/embed?" . $_SERVER['QUERY_STRING'];
    }

    // Request the calendar
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $buffer = curl_exec($ch);
    curl_close($ch);

    // Point stylesheet and javascript to custom versions
    $pattern = '/(<link.*>)/';
    $replacement = '<link rel="stylesheet" type="text/css" href="' . $stylesheet . '" />';
    $buffer = preg_replace($pattern, $replacement, $buffer);

    $pattern = '/src="(.*js)"/';
    $replacement = 'src="' . $javascript . '?$1"';
    $buffer = preg_replace($pattern, $replacement, $buffer);

    // Change image directory to absolute path
    $pattern = '"imagePath":"images/"';
    $replacement = '"imagePath":"https://www.google.com/calendar/images/"';
    $buffer = str_replace($pattern, $replacement, $buffer);

    // Add a hook to the window onload function
    $pattern = '/}\);}<\/script>/';
    $replacement = '}); restylegc();}</script>';
    $buffer = preg_replace($pattern, $replacement, $buffer);

    // Use DHTML to modify the DOM after the calendar loads
    $pattern = '/(<\/head>)/';
    $replacement = <<<RGC
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript">
function restylegc() {
    // remove inline style from body so background-color can be set using the stylesheet
    $('body').removeAttr('style');

    // iterate over each bubble and remove the width property from the style attribute
    // so that the width can be set using the stylesheet
    $('.bubble').each(function(){
        style = $(this).attr('style').replace(/width: \d+px;?/i, '');
        $(this).attr('style', style);
    });

    // see jQuery documentation for other ways to edit DOM
    // http://docs.jquery.com/
}
</script>
</head>
RGC;
    $buffer = preg_replace($pattern, $replacement, $buffer);

    // display the calendar
    $this->source = $buffer;
    sfConfig::set('sf_web_debug', false);
    $this->setLayout(false);
  }

  public function executeJs(sfWebRequest $request)
  {
    // Uncomment the JS download block and comment out the static JS block to use the
    // current calendar javascript instead of the statically-downloaded javascript
    //
    // Beware: Google may change their javascript without warning and you may get
    // bugs if you use the download block and don't stay on top of changes

    // START Javascript Download Block
//    // URL for the javascript
//    $url = "";
//    if(count($_GET) > 0) {
//      $url = "https://www.google.com/calendar/" . $_SERVER['QUERY_STRING'];
//    }
//    // Request the javascript
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//    $buffer = curl_exec($ch);
//    curl_close($ch);
    // END Javascript Download Block

    // START Static Javascript Block
    $jsFile = sfConfig::get('app_fs_restyle_google_calendar_plugin_js');
    $buffer = file_get_contents(__DIR__ . '/../../../lib/vendor/google/js/' . $jsFile);
    // END Static Javascript Block

    // Fix URLs in the javascript
    $pattern = '/this\.[a-zA-Z]{1,2}\+"calendar/';
    $replacement = '"https://www.google.com/calendar';
    $buffer = preg_replace($pattern, $replacement, $buffer);

    // Display the javascript
    $this->source = $buffer;
    sfConfig::set('sf_web_debug', false);
    $request->setRequestFormat('js');
    $this->setLayout(false);
  }
}