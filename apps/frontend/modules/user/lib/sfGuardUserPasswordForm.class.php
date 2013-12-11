<?php

/**
 * Description of sfGuardUserEditForm
 *
 * @author jeremy
 */
class sfGuardUserPasswordForm extends sfGuardUserForm
{
  public function configure()
  {
    parent::configure();
    $this->useFields(array('password', 'password_again'));
    $this->getWidgetSchema()->setNameFormat('password[%s]');
  }
}
