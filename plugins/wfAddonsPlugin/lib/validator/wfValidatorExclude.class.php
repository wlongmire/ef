<?php

/*
 * Copied version of sfValidatorBlacklist
 */
class wfValidatorExclude extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * forbidden_values: An array of forbidden values (required)
   *  * case_sensitive:   Case sensitive comparison (default true)
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('forbidden_values');
    $this->addOption('case_sensitive', true);
    $this->addMessage('forbidden', 'Value %value% is forbidden');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $forbiddenValues = $this->getOption('forbidden_values');
    if ($forbiddenValues instanceof sfCallable)
    {
      $forbiddenValues = $forbiddenValues->call();
    }

    $checkValue = $value;

    if (false === $this->getOption('case_sensitive'))
    {
      $checkValue = strtolower($checkValue);
      $forbiddenValues = array_map('strtolower', $forbiddenValues);
    }

    if (in_array($checkValue, $forbiddenValues))
    {
      throw new sfValidatorError($this, 'forbidden', array('value' => $value));
    }

    return $value;
  }
}