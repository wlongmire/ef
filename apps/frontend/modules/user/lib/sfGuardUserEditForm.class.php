<?php

/**
 * Description of sfGuardUserEditForm
 *
 * @author jeremy
 */
class sfGuardUserEditForm extends sfGuardUserForm
{
  public function configure()
  {
    parent::configure();
    $this->useFields(array('email_address', 'first_name', 'last_name'));
  }
}
