<?php 
if (!$site->logo_id)
{
  return;
}

$item = $site->Logo;
$dimensions = aDimensions::constrain($item['width'], $item['height'], $item['format'], array(
    'width' => '150',
    'resizeType' => 's',
    'height' => false
));

$url = url_for('a_media_image', array(
    'slug' => $item['slug'],
    'width' => $dimensions['width'],
    'height' => $dimensions['height'],
    'resizeType' => $dimensions['resizeType'],
    'format' => $dimensions['format']
));

echo sprintf('<img alt="%s" width="%s" height="%s" src="%s"/>',
  htmlentities($item['title'], ENT_COMPAT, 'UTF-8'),
  $dimensions['width'],
  $dimensions['height'],
  $url
);