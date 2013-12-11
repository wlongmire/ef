<?php

/**
 * aEvent form base class.
 *
 * @method aEvent getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 */
abstract class BaseaEventForm extends aBlogItemForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();


    $this->widgetSchema->setNameFormat('a_event[%s]');

  }

  public function getModelName()
  {
    return 'aEvent';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

  }

  protected function doUpdateObject($values)
  {
    parent::doUpdateObject($values);
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);
  }


}
