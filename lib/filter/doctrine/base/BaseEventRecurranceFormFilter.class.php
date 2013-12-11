<?php

/**
 * EventRecurrance filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEventRecurranceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'event_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Event'), 'add_empty' => true)),
      'start_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'end_date'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'start_time' => new sfWidgetFormFilterInput(),
      'end_time'   => new sfWidgetFormFilterInput(),
      'sunday'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'monday'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'tuesday'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'wednesday'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'thursday'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'friday'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'saturday'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'event_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Event'), 'column' => 'id')),
      'start_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'end_date'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'start_time' => new sfValidatorPass(array('required' => false)),
      'end_time'   => new sfValidatorPass(array('required' => false)),
      'sunday'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'monday'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'tuesday'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'wednesday'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'thursday'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'friday'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'saturday'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('event_recurrance_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'EventRecurrance';
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
      'sunday'     => 'Boolean',
      'monday'     => 'Boolean',
      'tuesday'    => 'Boolean',
      'wednesday'  => 'Boolean',
      'thursday'   => 'Boolean',
      'friday'     => 'Boolean',
      'saturday'   => 'Boolean',
    );
  }
}
