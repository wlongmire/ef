<?php

class wfValidatorDuration extends sfValidatorTime
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * min:       The minimum time, as either hh:mm or hh:mm:ss
   *  * max:       The maximum time, as either hh:mm or hh:mm:ss
   *
   * Available error codes:
   *
   *  * min
   *  * max
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorTime
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addMessage('min', 'Time must be more than %min%');
    $this->addMessage('max', 'Time must be less than %max%');

    $this->addOption('min');
    $this->addOption('max');
    $this->setOption('time_format', '/^(?P<hour>\d\d):(?P<minute>[0-5]\d):(?P<second>[0-5]\d)$/');
    $this->setOption('time_format_error', 'hh:mm:ss');
  }

  protected function doClean($value)
  {
    $clean = parent::doClean($value);
 
    if ($this->getOption('min') || $this->getOption('max'))
    {
      $time = $this->convertTime($clean);
      if ($this->getOption('min') && $time < $this->convertTime($this->getOption('min')))
      {
        throw new sfValidatorError($this, 'min', array('value' => $value, 'min' => $this->getOption('min')));
      }
      if ($this->getOption('max') && $time > $this->convertTime($this->getOption('max')))
      {
        throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => $this->getOption('max')));
      }      
    }
    
    return $clean;
  }

  protected function convertTime($time)
  {
    $tokens = explode(':', $time);
    $count = count($tokens);
    if ($count > 3 || $count < 2)
    {
      return $time; // if the time is already in seconds, return it. the pattern validator above will make sure that the entered time is in the right format
    }
    return $count == 3 ? (integer)($tokens[0] * 3600 + $tokens[1] * 60 + $tokens[2]) : (integer)($tokens[0] * 3600 + $tokens[1] * 60);
  }
}
