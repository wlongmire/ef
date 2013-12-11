<?php

/**
 * Profile filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'location_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => true)),
      'media_item_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Picture'), 'add_empty' => true)),
      'blurb'            => new sfWidgetFormFilterInput(),
      'is_group'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'home_zip_code'    => new sfWidgetFormFilterInput(),
      'studio_zip_code'  => new sfWidgetFormFilterInput(),
      'display_email'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'members_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Profile')),
      'disciplines_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Discipline')),
      'categories_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
      'owners_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'groups_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Profile')),
      'events_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Event')),
    ));

    $this->setValidators(array(
      'name'             => new sfValidatorPass(array('required' => false)),
      'location_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Location'), 'column' => 'id')),
      'media_item_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Picture'), 'column' => 'id')),
      'blurb'            => new sfValidatorPass(array('required' => false)),
      'is_group'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'home_zip_code'    => new sfValidatorPass(array('required' => false)),
      'studio_zip_code'  => new sfValidatorPass(array('required' => false)),
      'display_email'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'members_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Profile', 'required' => false)),
      'disciplines_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Discipline', 'required' => false)),
      'categories_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
      'owners_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'groups_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Profile', 'required' => false)),
      'events_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Event', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addMembersListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.ProfileGroupMember ProfileGroupMember')
      ->andWhereIn('ProfileGroupMember.member_profile_id', $values)
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
      ->leftJoin($query->getRootAlias().'.ProfileDiscipline ProfileDiscipline')
      ->andWhereIn('ProfileDiscipline.discipline_id', $values)
    ;
  }

  public function addCategoriesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.ProfileCategory ProfileCategory')
      ->andWhereIn('ProfileCategory.category_id', $values)
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
      ->leftJoin($query->getRootAlias().'.ProfileOwner ProfileOwner')
      ->andWhereIn('ProfileOwner.user_id', $values)
    ;
  }

  public function addGroupsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.ProfileGroupMember ProfileGroupMember')
      ->andWhereIn('ProfileGroupMember.group_profile_id', $values)
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
      ->leftJoin($query->getRootAlias().'.EventProfile EventProfile')
      ->andWhereIn('EventProfile.event_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Profile';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'name'             => 'Text',
      'location_id'      => 'ForeignKey',
      'media_item_id'    => 'ForeignKey',
      'blurb'            => 'Text',
      'is_group'         => 'Boolean',
      'home_zip_code'    => 'Text',
      'studio_zip_code'  => 'Text',
      'display_email'    => 'Boolean',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'members_list'     => 'ManyKey',
      'disciplines_list' => 'ManyKey',
      'categories_list'  => 'ManyKey',
      'owners_list'      => 'ManyKey',
      'groups_list'      => 'ManyKey',
      'events_list'      => 'ManyKey',
    );
  }
}
