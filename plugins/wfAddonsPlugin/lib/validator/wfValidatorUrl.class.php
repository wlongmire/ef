<?php
/**
 * wfValidatorString adds an option pattern and nohtml option to sfValidatorString
 *
 * @author     Jeremy Kauffman <kauffj@gmail.com>
 * @see        sfValidatorString
 */
class wfValidatorUrl extends sfValidatorString
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * pattern: A regex pattern compatible with PCRE (required)
   *  * nohtml: strip_html_entities
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorString
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addOption('default_protocol', 'http');
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $clean = parent::doClean($value);
    if (!filter_var($clean, FILTER_VALIDATE_URL))
    {
      if ($this->getOption('default_protocol'))
      {
        $attempt = $this->getOption('default_protocol') . '://' . $clean;
        if (filter_var($attempt, FILTER_VALIDATE_URL))
        {
          return $attempt;
        }
      }
      throw new sfValidatorError($this, 'invalid', array('value' => $clean));
    }
    
    return $clean;
  }
}