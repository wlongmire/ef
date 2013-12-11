<?php

/**
 * EventOccurance filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEventOccuranceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'event_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Event'), 'add_empty' => true)),
      'start_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'end_date'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'start_time' => new sfWidgetFormFilterInput(),
      'end_time'   => new sfWidgetFormFilterInput(),
      'ticket_url' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'event_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Event'), 'column' => 'id')),
      'start_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'end_date'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'start_time' => new sfValidatorPass(array('required' => false)),
      'end_time'   => new sfValidatorPass(array('required' => false)),
      'ticket_url' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event_occurance_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EventOccurance';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'event_id'   => 'ForeignKey',
      'start_date' => 'Date',
      'end_date'   => 'Date',
      'start_time' => 'Text',
      'end_time'   => 'Text',
      'ticket_url' => 'Text',
    );
  }
}
