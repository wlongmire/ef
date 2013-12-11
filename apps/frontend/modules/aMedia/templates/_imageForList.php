<?php 
if (!$item)
{
  return;
}

$options = array('resizeType' => 's');
if ($item['width'] >= $item['height'])
{
  $options['width'] = 120;
  $options['height'] = false;
}
else
{
  $options['width'] = false;
  $options['height'] = 120;  
}
$dimensions = aDimensions::constrain($item['width'], $item['height'], $item['format'], $options);
$useAbsoluteUrl = (boolean)$sf_request['callback'];

$url = url_for('a_media_image', array(
    'slug' => $item['slug'],
    'width' => $dimensions['width'],
    'height' => $dimensions['height'],
    'resizeType' => $dimensions['resizeType'],
    'format' => $dimensions['format']
), $useAbsoluteUrl);

echo sprintf('<img alt="%s" width="%s" height="%s" src="%s"/>',
  htmlentities($item['title'], ENT_COMPAT, 'UTF-8'),
  $dimensions['width'],
  $dimensions['height'],
  $url
);