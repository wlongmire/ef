<?php
/**
 * wfValidatorStringEmail validates an email using Doctrine_Validator_Email
 *
 * @author     Jeremy Kauffman <kauffj@gmail.com>
 * @see        wfValidatorString
 */
class wfValidatorStringEmail extends wfValidatorString
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * pattern: A regex pattern compatible with PCRE (required)
   *  * strip_html_entities
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorString
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('check_mx', false);
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    $validator = new Doctrine_Validator_Email();
    $validator->args = array('check_mx' => $this->getOption('check_mx'));
    if ($validator->validate($clean) == false)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    
    return $clean;
  }
}
