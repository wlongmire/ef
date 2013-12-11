<?php

class wfValidatorTime extends sfValidatorTime
{
  /**
   * Configures the current validator. This extends sfValidatorTime to add support for meridiem and min/max times.
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
    $this->setOption('time_format', '/^(?P<hour>\d\d):(?P<minute>[0-5]\d)\s?(?P<meridiem>am|pm|AM|PM)?$/');
    $this->setOption('time_format_error', 'hh:mm:ss AM|PM');
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
    $tokens = preg_split('/[\s:]/', trim($time));
    $count = count($tokens);
    if ($count == 1)
    {
      return $time; // if the time is already in seconds, return it. the pattern validator above will make sure that the entered time is in the right format
    }
    $meridiem = strtolower(end($tokens)); //may or may not be present
    if (in_array($meridiem, array('am', 'pm')))
    {
      if ($meridiem == 'pm')
      {
        $tokens[0] += 12;
      }
      if ($tokens[0] % 12 == 0) // because 12am = 0 hours and 12pm = 12 hours
      {
        $tokens[0] -= 12;
      }
      $count--; //there's one less time value
    }
    return $count == 3 ? (integer)($tokens[0] * 3600 + $tokens[1] * 60 + $tokens[2]) : (integer)($tokens[0] * 3600 + $tokens[1] * 60);
  }

  /**
   * Converts an array representing a time to a timestamp.
   *
   * The array can contains the following keys: hour, minute, second
   *
   * @param  array $value  An array of date elements
   *
   * @return int A timestamp
   */
  protected function convertTimeArrayToTimestamp($value)
  {
    // all time elements must be empty or a number
    foreach (array('hour', 'minute', 'second') as $key)
    {
      if (isset($value[$key]) && !preg_match('#^\d+$#', $value[$key]) && !empty($value[$key]))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    // if second is set, minute and hour must be set
    // if minute is set, hour must be set
    if (
      $this->isValueSet($value, 'second') && (!$this->isValueSet($value, 'minute') || !$this->isValueSet($value, 'hour'))
      ||
      $this->isValueSet($value, 'minute') && !$this->isValueSet($value, 'hour')
    )
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }


    if (isset($value['meridiem']) && !empty($value['meridiem']))
    {
      $meridiem = strtolower($value['meridiem']);
      if (!in_array($meridiem, array('am', 'pm')))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
      if ($meridiem == 'pm' && isset($value['hour']))
      {
        $value['hour'] += 12;
      }
      if ($value['hour'] % 12 == 0) // because 12am = 0 hours and 12pm = 12 hours
      {
        $value['hour'] -= 12;
      }

    }
    
    $clean = mktime(
      isset($value['hour']) ? intval($value['hour']) : 0,
      isset($value['minute']) ? intval($value['minute']) : 0,
      isset($value['second']) ? intval($value['second']) : 0
    );

    if (false === $clean)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => var_export($value, true)));
    }

    return $clean;
  }
}
