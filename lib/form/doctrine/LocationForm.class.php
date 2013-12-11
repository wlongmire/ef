<?php

/**
 * Location form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LocationForm extends BaseLocationForm
{
  public function configure()
  {
    $this->useFields(array('name'));
    
    $this->setWidget('parent_id', new myWidgetFormDoctrineChoiceNestedSet(array(
      'model' => 'Location',
      'order_by' => array('name', 'ASC')
    )));
    $this->setValidator('parent_id', new sfValidatorDoctrineChoice(array(
      'required' => false,
      'model' => 'Location'
    )));
    
    $this->setDefault('parent_id', $this->object->getNode()->getParentId());
    $this->getWidgetSchema()->setLabel('parent_id', 'Child of');    
  }
  
  protected function doUpdateObject($values)
  {
    $this->parentId = $values['parent_id'];    
    unset($values['parent_id']);
    return parent::doUpdateObject($values);
  }
  
  protected function doSave($con = null)
  { 
    parent::doSave($con);

    $this->object->getNode()->setParentId($this->parentId); //will save record
  }  
}
