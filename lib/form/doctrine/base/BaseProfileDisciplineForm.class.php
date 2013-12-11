<?php

/**
 * ProfileDiscipline form base class.
 *
 * @method ProfileDiscipline getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProfileDisciplineForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_id'    => new sfWidgetFormInputHidden(),
      'discipline_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'profile_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('profile_id')), 'empty_value' => $this->getObject()->get('profile_id'), 'required' => false)),
      'discipline_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('discipline_id')), 'empty_value' => $this->getObject()->get('discipline_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_discipline[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileDiscipline';
  }

}
