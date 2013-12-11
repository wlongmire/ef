<?php
/**
 * Doctrine_Validator_Exclude
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @author      Jeremy Kauffman <kauffj@gmail.com>
 */
class Doctrine_Validator_Exclude
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
      return !in_array($value, $this->args);
    }
}