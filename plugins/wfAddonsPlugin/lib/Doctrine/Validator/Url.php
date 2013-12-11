<?php
/**
 * Doctrine_Validator_Url
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @author      Jeremy Kauffman <kauffj@gmail.com>
 */
class Doctrine_Validator_Url
{
    /**
     * checks that the value isn't in a list of forbidden values
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
      return filter_var($value, FILTER_VALIDATE_URL);
    }
}