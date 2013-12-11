<?php
/**
 * wfValidatorCSRFToken Task [description]
 *
 * @author jeremy
 */
class wfValidatorCSRFToken extends sfValidatorCSRFToken
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addMessage('csrf_attack', 'Your session has expired. Please return to the home page and try again.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    try {
      return parent::doClean($value);
    } catch (sfValidatorError $e) {
      throw new sfValidatorErrorSchema($this, array($e));
    }
  }
}
