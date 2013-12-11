<?php

/**
 * SiteDiscipline form base class.
 *
 * @method SiteDiscipline getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSiteDisciplineForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'site_id'       => new sfWidgetFormInputHidden(),
      'discipline_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'site_id'       => new sfValidatorChoice(array('choices' => array($this->getObject()->get('site_id')), 'empty_value' => $this->getObject()->get('site_id'), 'required' => false)),
      'discipline_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('discipline_id')), 'empty_value' => $this->getObject()->get('discipline_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('site_discipline[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SiteDiscipline';
  }

}
