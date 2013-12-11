<?php 
extract(array(
    'options' => array(),
    'variant' => false
), EXTR_SKIP);

$useAbsoluteUrls = (boolean)$sf_request['callback'];

if ($item->type == 'image')
{
  if ($variant)
  {
    $options = array_merge(aTools::getSlotVariantOptions('aImage', $variant), $options); //passed in options always take precedence
  }

  $dimensions = aDimensions::constrain($item->width, $item->height, $item->format, $options);

  echo str_replace(array("_WIDTH_", "_HEIGHT_", "_c-OR-s_", "_FORMAT_"),
                       array($dimensions['width'], $dimensions['height'], $dimensions['resizeType'], $dimensions['format']),
                       $item->getEmbedCode('_WIDTH_', '_HEIGHT_', '_c-OR-s_', '_FORMAT_', $useAbsoluteUrls));
}