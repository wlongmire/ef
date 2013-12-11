<?php
/**
 * Doctrine_Validator_NoHtml
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @author      Jeremy Kauffman <kauffj@gmail.com>
 */
class Doctrine_Validator_Nohtml
{
    /**
     * checks if given value contains HTML
     * 
     * @param mixed $value
     * @return boolean
     */
    public function validate($value)
    {
      if (is_null($value))
      {
        return true;
      }
      return strip_tags($value) == $value;
    }
}