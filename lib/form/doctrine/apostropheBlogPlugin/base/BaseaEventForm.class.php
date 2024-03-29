<?php

/**
 * aEvent form base class.
 *
 * @method aEvent getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
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

}
