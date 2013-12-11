<?php

/**
 * Description of sfGuardUserAdminForm
 *
 * @author jeremy
 */
class sfGuardUserAdminForm extends BasesfGuardUserAdminForm 
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('first_name', new sfWidgetFormInputText());
    $this->setWidget('last_name', new sfWidgetFormInputText());    
    
    $this->useFields(array('first_name', 'last_name', 'email_address', 'password', 'password_again', 'groups_list'));
    $this->disableAutocomplete();
  }
}
