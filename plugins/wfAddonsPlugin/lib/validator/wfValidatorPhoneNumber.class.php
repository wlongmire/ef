<?php
class wfValidatorPhoneNumber extends wfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setOption('pattern', '/^\d{10}$/');
  }

  protected function doClean($value)
  {
    $clean = preg_replace('/[^0-9]/', '', $value); // strip anything that's not a digit
    return parent::doClean($clean);
  }
}