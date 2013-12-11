<?php
/**
 * wfValidatorDateRange extends sfValidatorDateRange to validate empty values
 *
 * @author     Jeremy Kauffman <kauffj@gmail.com>
 * @see        sfValidatorDateRange
 */
class wfValidatorDateRange extends sfValidatorDateRange
{
  /**
   * Configures the current validator.
   *
   * Has all the options of sfValidatorDateRange plus:
   *
   *  * empty_field: The name of the "set_null" field (default: set_null)
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addOption('empty_field', 'set_null');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $emptyField = $this->getOption('empty_field');

    if (isset($value[$emptyField]))
    {
      $emptyValidator = new sfValidatorBoolean(); //if empty field is provided validate it
      $value[$emptyField] = $emptyValidator->clean($value[$emptyField]);
      if ($value[$emptyField]) //if it is valid and true, shortcircuit clean to return empty date values
      {
        $value[$this->getOption('from_field')] = null;
        $value[$this->getOption('to_field')] = null;
        return $value;
      }
    }

    return parent::doClean($value);
  }
}