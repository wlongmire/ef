<?php

/**
 * Venue filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseVenueFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'blurb'            => new sfWidgetFormFilterInput(),
      'location_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => true)),
      'media_item_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Picture'), 'add_empty' => true)),
      'url'              => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'address_1'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'address_2'        => new sfWidgetFormFilterInput(),
      'city'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'state'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'zip_code'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'venue_types_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'VenueType')),
      'owners_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'name'             => new sfValidatorPass(array('required' => false)),
      'blurb'            => new sfValidatorPass(array('required' => false)),
      'location_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Location'), 'column' => 'id')),
      'media_item_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Picture'), 'column' => 'id')),
      'url'              => new sfValidatorPass(array('required' => false)),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'address_1'        => new sfValidatorPass(array('required' => false)),
      'address_2'        => new sfValidatorPass(array('required' => false)),
      'city'             => new sfValidatorPass(array('required' => false)),
      'state'            => new sfValidatorPass(array('required' => false)),
      'zip_code'         => new sfValidatorPass(array('required' => false)),
      'venue_types_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'VenueType', 'required' => false)),
      'owners_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('venue_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addVenueTypesListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.VenueVenueType VenueVenueType')
      ->andWhereIn('VenueVenueType.venue_type_id', $values)
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
      ->leftJoin($query->getRootAlias().'.VenueOwner VenueOwner')
      ->andWhereIn('VenueOwner.user_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Venue';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'name'             => 'Text',
      'blurb'            => 'Text',
      'location_id'      => 'ForeignKey',
      'media_item_id'    => 'ForeignKey',
      'url'              => 'Text',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'address_1'        => 'Text',
      'address_2'        => 'Text',
      'city'             => 'Text',
      'state'            => 'Text',
      'zip_code'         => 'Text',
      'venue_types_list' => 'ManyKey',
      'owners_list'      => 'ManyKey',
    );
  }
}
