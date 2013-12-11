<?php

/**
 * Description of JoinForm
 *
 * @author jeremy
 */
class JoinForm extends sfGuardUserForm
{
  public function configure()
  {
    parent::configure();
    $this->useFields(array('email_address', 'first_name', 'last_name', 'password', 'password_again'));
    $this->getWidgetSchema()->setHelp('last_name', 'Real name, please. Your displayed profile name may be different.');
    $this->getWidgetSchema()->setFormFormatterName('AAdmin');
  }
}
