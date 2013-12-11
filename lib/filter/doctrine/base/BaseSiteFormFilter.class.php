<?php

/**
 * Site filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSiteFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'org_abbr'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'org_name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'theme'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mode'              => new sfWidgetFormChoice(array('choices' => array('' => '', 'listing' => 'listing', 'entry' => 'entry'))),
      'domain'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'logo_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Logo'), 'add_empty' => true)),
      'location_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'tag_headings_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TagHeading')),
      'disciplines_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Discipline')),
    ));

    $this->setValidators(array(
      'name'              => new sfValidatorPass(array('required' => false)),
      'org_abbr'          => new sfValidatorPass(array('required' => false)),
      'org_name'          => new sfValidatorPass(array('required' => false)),
      'theme'             => new sfValidatorPass(array('required' => false)),
      'mode'              => new sfValidatorChoice(array('required' => false, 'choices' => array('listing' => 'listing', 'entry' => 'entry'))),
      'domain'            => new sfValidatorPass(array('required' => false)),
      'logo_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Logo'), 'column' => 'id')),
      'location_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Location'), 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'tag_headings_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TagHeading', 'required' => false)),
      'disciplines_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Discipline', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('site_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addTagHeadingsListColumnQuery(Doctrine_Query $query, $field, $values)
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
      ->leftJoin($query->getRootAlias().'.SiteTagHeading SiteTagHeading')
      ->andWhereIn('SiteTagHeading.tag_heading_id', $values)
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
      ->leftJoin($query->getRootAlias().'.SiteDiscipline SiteDiscipline')
      ->andWhereIn('SiteDiscipline.discipline_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'Site';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'name'              => 'Text',
      'org_abbr'          => 'Text',
      'org_name'          => 'Text',
      'theme'             => 'Text',
      'mode'              => 'Enum',
      'domain'            => 'Text',
      'logo_id'           => 'ForeignKey',
      'location_id'       => 'ForeignKey',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'tag_headings_list' => 'ManyKey',
      'disciplines_list'  => 'ManyKey',
    );
  }
}
