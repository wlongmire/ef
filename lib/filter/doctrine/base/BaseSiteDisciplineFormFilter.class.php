<?php

/**
 * SiteDiscipline filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseSiteDisciplineFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
    ));

    $this->setValidators(array(
    ));

    $this->widgetSchema->setNameFormat('site_discipline_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SiteDiscipline';
  }

  public function getFields()
  {
    return array(
      'site_id'       => 'Number',
      'discipline_id' => 'Number',
    );
  }
}
