<?php
class EventSearchFilter extends BaseForm
{
  public function configure()
  {
    $this->setWidget('name', new sfWidgetFormInputText(array(
        'label' => 'Search'
    ), array(
        'class' => 'search',
        'placeholder' => 'Search'
    )));
    $this->setValidator('name', new sfValidatorString(array('required' => false)));
    
    $this->getWidgetSchema()->setNameFormat('event_search[%s]');
  }
}
