<?php

/**
 * EventRecurrance form base class.
 *
 * @method EventRecurrance getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEventRecurranceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'event_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Event'), 'add_empty' => false)),
      'start_date' => new sfWidgetFormDate(),
      'end_date'   => new sfWidgetFormDate(),
      'start_time' => new sfWidgetFormTime(),
      'end_time'   => new sfWidgetFormTime(),
      'sunday'     => new sfWidgetFormInputCheckbox(),
      'monday'     => new sfWidgetFormInputCheckbox(),
      'tuesday'    => new sfWidgetFormInputCheckbox(),
      'wednesday'  => new sfWidgetFormInputCheckbox(),
      'thursday'   => new sfWidgetFormInputCheckbox(),
      'friday'     => new sfWidgetFormInputCheckbox(),
      'saturday'   => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'event_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Event'))),
      'start_date' => new sfValidatorDate(),
      'end_date'   => new sfValidatorDate(),
      'start_time' => new sfValidatorTime(array('required' => false)),
      'end_time'   => new sfValidatorTime(array('required' => false)),
      'sunday'     => new sfValidatorBoolean(array('required' => false)),
      'monday'     => new sfValidatorBoolean(array('required' => false)),
      'tuesday'    => new sfValidatorBoolean(array('required' => false)),
      'wednesday'  => new sfValidatorBoolean(array('required' => false)),
      'thursday'   => new sfValidatorBoolean(array('required' => false)),
      'friday'     => new sfValidatorBoolean(array('required' => false)),
      'saturday'   => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event_recurrance[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EventRecurrance';
  }

}
