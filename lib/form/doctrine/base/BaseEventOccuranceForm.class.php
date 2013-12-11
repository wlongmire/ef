<?php

/**
 * EventOccurance form base class.
 *
 * @method EventOccurance getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEventOccuranceForm extends BaseFormDoctrine
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
      'ticket_url' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'event_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Event'))),
      'start_date' => new sfValidatorDate(),
      'end_date'   => new sfValidatorDate(array('required' => false)),
      'start_time' => new sfValidatorTime(array('required' => false)),
      'end_time'   => new sfValidatorTime(array('required' => false)),
      'ticket_url' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event_occurance[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EventOccurance';
  }

}
