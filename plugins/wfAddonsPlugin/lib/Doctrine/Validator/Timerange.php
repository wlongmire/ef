<?php
/**
 * Doctrine_Validator_Timerange
 *
 * @package     Doctrine
 * @subpackage  Validator
 * @author      Jeremy Kauffman <kauffj@gmail.com>
 */
class Doctrine_Validator_Timerange
{
    /**
     * checks if given value contains HTML
     * 
     * @param mixed $value, value should either be a time string or a number of seconds
     * @return boolean
     */
    public function validate($value)
    {
      if (is_null($value))
      {
        return true;
      }
      $time = $this->_convertTimeToSeconds($value);
      if (isset($this->args[0]) && $time < $this->_convertTimeToSeconds($this->args[0]))
      {
        return false;
      }
      if (isset($this->args[1]) && $time > $this->_convertTimeToSeconds($this->args[1]))
      {
        return false;
      }
      return true;
    }
    
    protected function _convertTimeToSeconds($time)
    {      
      $tokens = explode(':', $time);
      $count = count($tokens);
      if ($count > 3 || $count < 2)
      {
        throw new Doctrine_Exception(sprintf('"%s" must be in the format hh:mm:ss or hh:mm', $time));
      }
      return $count == 3 ? (integer)($tokens[0] * 3600 + $tokens[1] * 60 + $tokens[2]) : (integer)($tokens[0] * 3600 + $tokens[1] * 60);
    }
}