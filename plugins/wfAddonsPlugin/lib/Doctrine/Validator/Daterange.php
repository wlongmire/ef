<?php
/**
 * Doctrine_Validator_Daterange
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @author      Jeremy Kauffman <kauffj@gmail.com>
 */
class Doctrine_Validator_Daterange
{
    /**
     * checks if given value contains HTML
     * 
     * @param mixed $value, value should either be a string capable of being handled by DateTime, or a string beginning with a + or - that is a modification of the current time.
     * @return boolean
     */
    public function validate($value)
    {
      if (is_null($value))
      {
        return true;
      }
      $dt = new DateTime($value);
      if (isset($this->args[0]) && $dt < (new DateTime($this->args[0])))
      {
        return false;
      }
      if (isset($this->args[1]) && $dt > (new DateTime($this->args[1])))
      {
        return false;
      }
      return true;
    }
}