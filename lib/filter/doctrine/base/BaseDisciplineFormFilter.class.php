<?php

/**
 * Discipline filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseDisciplineFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'          => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'root_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Root'), 'add_empty' => true)),
      'lft'           => new sfWidgetFormFilterInput(),
      'rgt'           => new sfWidgetFormFilterInput(),
      'level'         => new sfWidgetFormFilterInput(),
      'profiles_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Profile')),
      'events_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Event')),
      'sites_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Site')),
    ));

    $this->setValidators(array(
      'name'          => new sfValidatorPass(array('required' => false)),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'root_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Root'), 'column' => 'id')),
      'lft'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profiles_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Profile', 'required' => false)),
      'events_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Event', 'required' => false)),
      'sites_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Site', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('discipline_filters[%s]');

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
      ->leftJoin($query->getRootAlias().'.ProfileDiscipline ProfileDiscipline')
      ->andWhereIn('ProfileDiscipline.profile_id', $values)
    ;
  }

  public function addEventsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('EventDiscipline.event_id', $values)
    ;
  }

  public function addSitesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.SiteDiscipline SiteDiscipline')
      ->andWhereIn('SiteDiscipline.site_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Discipline';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'name'          => 'Text',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
      'root_id'       => 'ForeignKey',
      'lft'           => 'Number',
      'rgt'           => 'Number',
      'level'         => 'Number',
      'profiles_list' => 'ManyKey',
      'events_list'   => 'ManyKey',
      'sites_list'    => 'ManyKey',
    );
  }
}
