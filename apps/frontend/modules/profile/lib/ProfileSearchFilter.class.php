<?php
class ProfileSearchFilter extends BaseForm
{
  public function configure()
  {
    $this->setWidget('name', new sfWidgetFormInputText(array(
        'label' => 'Search'
    ), array(
        'class' => 'search'
    )));
    $this->setValidator('name', new sfValidatorString(array('required' => false)));
    
    $this->getWidgetSchema()->setNameFormat('profile_search[%s]');
  }
}
