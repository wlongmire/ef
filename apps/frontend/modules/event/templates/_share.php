<?php
$id = 'share-button-' . aGuid::generate();
$encodedEventUrl = urlencode(url_for('event_show', Event::parameterize($event), true));
$encodedEventUrl = 'http://www.eventsfilter.com';
if ($event->Picture)
{ 
  $item = $event->Picture;
  $options = aTools::getSlotVariantOptions('aImage', 'detail');
  $dimensions = aDimensions::constrain($item->width, $item->height, $item->format, $options);
  $imageUrl = urlencode($item->getImgSrcUrl($dimensions['width'], $dimensions['height'], $dimensions['resizeType'], $dimensions['format'], true, $options));
}
else
{
  $imageUrl = 0;
}
//$facebookUrl = sprintf('http://www.facebook.com/dialog/feed?link=%s&picture=%s&name=%s&redirect=%s',
//                                $encodedEventUrl, $imageUrl, urlencode($event['name']), $encodedEventUrl); 
$facebookUrl = sprintf('http://www.facebook.com/dialog/feed?link=%s',
                                $encodedEventUrl); 
?>
<ul class="a-ui a-controls">
  <li class="a-options-container">
    <?php echo my_a_js_button(__('Share'), array('a-add', 'icon'), array('id' => $id)) ?>
    <ul class="a-ui a-options a-area-options dropshadow">
      <li class="a-options-item">
        <?php echo my_a_button(__('Facebook'), $facebookUrl, array('icon', 'calendar', 'facebook', 'no-bg')) ?>
      </li>
      <li class="a-options-item">
        <?php echo my_a_button('Outlook XP/2003', $facebookUrl, array('icon', 'calendar', 'twitter', 'no-bg')) ?>
      </li> 
    </ul>
    <?php a_js_call('apostrophe.menuToggle(?)', array('button' => '#' . $id, 'classname' => 'a-options-open', 'overlay' => false)) ?>
  </li>
</ul>

<?php /*
 * <script>function fbs_click() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script><style> html .fb_share_link { padding:2px 0 0 20px; height:16px; background:url(http://static.ak.facebook.com/images/share/facebook_share_icon.gif?6:26981) no-repeat top left; }</style><a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank" class="fb_share_link">Share on Facebook</a>
 */ ?>