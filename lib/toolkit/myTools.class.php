<?php

/**
 * Description of myTools
 *
 * @author jeremy
 */
class myTools 
{
  public static function urlify($string)
  {
    return strtolower(trim(
        preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace(
            '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', 
            '$1', 
            htmlentities($string, ENT_QUOTES, 'UTF-8')
        ), ENT_QUOTES, 'UTF-8')), 
    '-'));    
  }
  
  public static function blurbToMeta($blurb)
  {
    return substr(aHtml::toPlaintext($blurb), 0, 300);
  }
}
