<?php

/**
 * EventProfile form base class.
 *
 * @method EventProfile getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEventProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'event_id'   => new sfWidgetFormInputHidden(),
      'profile_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'event_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('event_id')), 'empty_value' => $this->getObject()->get('event_id'), 'required' => false)),
      'profile_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_id')), 'empty_value' => $this->getObject()->get('profile_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event_profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EventProfile';
  }

}
