<?php

/**
 * Description of AdminTagForm
 *
 * @author jeremy
 */
class AdminTagForm extends aTagForm
{
  public function configure()
  {
    parent::configure();
    
    $this->setWidget('tag_heading_id', new wfWidgetFormDoctrineChoice(array(
        'model' => 'TagHeading', 
        'add_empty' => '(no heading)'
    )));
    
    $this->setValidator('tag_heading_id', new wfValidatorDoctrineChoice(array('model' => 'TagHeading')));    
  }
}
