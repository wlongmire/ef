<?php

/**
 * TagHeading filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTagHeadingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'       => new sfWidgetFormFilterInput(),
      'sites_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Site')),
    ));

    $this->setValidators(array(
      'name'       => new sfValidatorPass(array('required' => false)),
      'sites_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Site', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tag_heading_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
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
      ->leftJoin($query->getRootAlias().'.SiteTagHeading SiteTagHeading')
      ->andWhereIn('SiteTagHeading.site_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'TagHeading';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'name'       => 'Text',
      'sites_list' => 'ManyKey',
    );
  }
}
