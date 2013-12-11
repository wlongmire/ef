<?php

/**
 * Event filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseEventFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'venue_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Venue'), 'add_empty' => true)),
      'event_type_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EventType'), 'add_empty' => true)),
      'media_item_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Picture'), 'add_empty' => true)),
      'name'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'blurb'                => new sfWidgetFormFilterInput(),
      'min_cost'             => new sfWidgetFormFilterInput(),
      'max_cost'             => new sfWidgetFormFilterInput(),
      'url'                  => new sfWidgetFormFilterInput(),
      'ticket_url'           => new sfWidgetFormFilterInput(),
      'is_published'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'suggested_venue_name' => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'profiles_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Profile')),
      'disciplines_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Discipline')),
      'owners_list'          => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'venue_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Venue'), 'column' => 'id')),
      'event_type_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('EventType'), 'column' => 'id')),
      'media_item_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Picture'), 'column' => 'id')),
      'name'                 => new sfValidatorPass(array('required' => false)),
      'blurb'                => new sfValidatorPass(array('required' => false)),
      'min_cost'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'max_cost'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'url'                  => new sfValidatorPass(array('required' => false)),
      'ticket_url'           => new sfValidatorPass(array('required' => false)),
      'is_published'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'suggested_venue_name' => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'profiles_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Profile', 'required' => false)),
      'disciplines_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Discipline', 'required' => false)),
      'owners_list'          => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addProfilesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.EventProfile EventProfile')
      ->andWhereIn('EventProfile.profile_id', $values)
    ;
  }

  public function addDisciplinesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.EventDiscipline EventDiscipline')
      ->andWhereIn('EventDiscipline.discipline_id', $values)
    ;
  }

  public function addOwnersListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.EventOwner EventOwner')
      ->andWhereIn('EventOwner.user_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Event';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'venue_id'             => 'ForeignKey',
      'event_type_id'        => 'ForeignKey',
      'media_item_id'        => 'ForeignKey',
      'name'                 => 'Text',
      'blurb'                => 'Text',
      'min_cost'             => 'Number',
      'max_cost'             => 'Number',
      'url'                  => 'Text',
      'ticket_url'           => 'Text',
      'is_published'         => 'Boolean',
      'suggested_venue_name' => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'profiles_list'        => 'ManyKey',
      'disciplines_list'     => 'ManyKey',
      'owners_list'          => 'ManyKey',
    );
  }
}
