<?php

/**
 * sfGuardUser filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'first_name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'email_address'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'algorithm'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'salt'                   => new sfWidgetFormFilterInput(),
      'password'               => new sfWidgetFormFilterInput(),
      'is_active'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_super_admin'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'last_login'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'full_name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'profile_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Profile'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'groups_list'            => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
      'categories_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
      'blog_editor_items_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem')),
      'owned_profiles_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Profile')),
      'owned_events_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Event')),
      'owned_venues_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Venue')),
    ));

    $this->setValidators(array(
      'first_name'             => new sfValidatorPass(array('required' => false)),
      'last_name'              => new sfValidatorPass(array('required' => false)),
      'email_address'          => new sfValidatorPass(array('required' => false)),
      'username'               => new sfValidatorPass(array('required' => false)),
      'algorithm'              => new sfValidatorPass(array('required' => false)),
      'salt'                   => new sfValidatorPass(array('required' => false)),
      'password'               => new sfValidatorPass(array('required' => false)),
      'is_active'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_super_admin'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'last_login'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'full_name'              => new sfValidatorPass(array('required' => false)),
      'profile_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Profile'), 'column' => 'id')),
      'created_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'groups_list'            => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
      'categories_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
      'blog_editor_items_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem', 'required' => false)),
      'owned_profiles_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Profile', 'required' => false)),
      'owned_events_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Event', 'required' => false)),
      'owned_venues_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Venue', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
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
      ->leftJoin($query->getRootAlias().'.sfGuardUserGroup sfGuardUserGroup')
      ->andWhereIn('sfGuardUserGroup.group_id', $values)
    ;
  }

  public function addPermissionsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.sfGuardUserPermission sfGuardUserPermission')
      ->andWhereIn('sfGuardUserPermission.permission_id', $values)
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
      ->leftJoin($query->getRootAlias().'.aCategoryUser aCategoryUser')
      ->andWhereIn('aCategoryUser.category_id', $values)
    ;
  }

  public function addBlogEditorItemsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.aBlogEditor aBlogEditor')
      ->andWhereIn('aBlogEditor.blog_item_id', $values)
    ;
  }

  public function addOwnedProfilesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('ProfileOwner.profile_id', $values)
    ;
  }

  public function addOwnedEventsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->andWhereIn('EventOwner.event_id', $values)
    ;
  }

  public function addOwnedVenuesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.VenueOwner VenueOwner')
      ->andWhereIn('VenueOwner.venue_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'first_name'             => 'Text',
      'last_name'              => 'Text',
      'email_address'          => 'Text',
      'username'               => 'Text',
      'algorithm'              => 'Text',
      'salt'                   => 'Text',
      'password'               => 'Text',
      'is_active'              => 'Boolean',
      'is_super_admin'         => 'Boolean',
      'last_login'             => 'Date',
      'full_name'              => 'Text',
      'profile_id'             => 'ForeignKey',
      'created_at'             => 'Date',
      'updated_at'             => 'Date',
      'groups_list'            => 'ManyKey',
      'permissions_list'       => 'ManyKey',
      'categories_list'        => 'ManyKey',
      'blog_editor_items_list' => 'ManyKey',
      'owned_profiles_list'    => 'ManyKey',
      'owned_events_list'      => 'ManyKey',
      'owned_venues_list'      => 'ManyKey',
    );
  }
}
