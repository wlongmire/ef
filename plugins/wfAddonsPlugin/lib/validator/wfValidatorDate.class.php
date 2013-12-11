<?php

class wfValidatorDate extends sfValidatorDate
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * date_format:             A regular expression that dates must match
   *  * with_time:               true if the validator must return a time, false otherwise
   *  * date_output:             The format to use when returning a date (default to Y-m-d)
   *  * datetime_output:         The format to use when returning a date with time (default to Y-m-d H:i:s)
   *  * date_format_error:       The date format to use when displaying an error for a bad_format error (use date_format if not provided)
   *  * max:                     The maximum date allowed (as a timestamp)
   *  * min:                     The minimum date allowed (as a timestamp)
   *  * max_dt:                  The maximum date allowed (as a valid DateTime string) - uses the same message as min/max, as well as date_format_range_error
   *  * min_dt:                  The minimum date allowed (as a valid DateTime string) - uses the same message as min/max, as well as date_format_range_error
   *  * date_format_range_error: The date format to use when displaying an error for min/max (default to d/m/Y H:i:s)
   *
   * Available error codes:
   *
   *  * bad_format
   *  * min
   *  * max
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addOption('min_dt', null);
    $this->addOption('max_dt', null);
    $this->setOption('date_format_range_error', 'm/d/Y');
    $this->setMessage('required', 'Required');
  }

  /**
   * @see sfValidatorDate
   */
  protected function doClean($value)
  {
    $clean = parent::doClean($value);
    
    try
    {
      $dt = new DateTime($clean);
    } 
    catch (Exception $e)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $clean));	
    }
    if ($this->hasOption('max_dt'))
    {
      $maxDt = $this->getOption('max_dt') instanceof DateTime ? $this->getOption('max_dt') : new DateTime($this->getOption('max_dt'));
      if ($dt > $maxDt)
      {
        throw new sfValidatorError($this, 'max', array('value' => $clean, 'max' => $maxDt->format($this->getOption('date_format_range_error'))));
      }
    }
    if ($this->hasOption('min_dt') && $dt < ($minDt = new DateTime()))
    {
      $minDt = $this->getOption('min_dt') instanceof DateTime ? $this->getOption('min_dt') : new DateTime($this->getOption('min_dt'));
      if ($dt < $minDt)
      {
        throw new sfValidatorError($this, 'min', array('value' => $clean, 'min' => $minDt->format($this->getOption('date_format_range_error'))));
      }
    }  

    return $clean;
  }
}
