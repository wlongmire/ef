<?php
/**
 * Description of my_lessc
 *
 * @author jeremy
 */
class my_lessc extends lessc
{
  /**
   * Called like:
   * filter: background_transparent_ie(@bg-color, @bg-opacity, 0); (IE 7-)
   * -ms-filter: background_transparent_ie(@bg-color, @bg-opacity, 1); (IE 8+)
   */
  public function lib_background_transparent_ie($value)
  {
    list($color, $opacity, $quoted) = explode(',', $this->compileValue($value));
    $quote = $quoted ? '"' : '';
    $argb = '#' . dechex($opacity * 255) . substr($color, 1);
    return "${quote}progid:DXImageTransform.Microsoft.gradient(startColorstr=$argb, endColorstr=$argb)${quote}";
  }
}