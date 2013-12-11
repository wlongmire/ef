<?php

/**
 * Tagging form base class.
 *
 * @package    eventsfilter
 * @subpackage tagging * @author     Jeremy Kauffman
 */
abstract class BaseTaggingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(array(), array()),
      'tag_id'         => new wfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tag'), 'add_empty' => false), array()),
      'taggable_model' => new sfWidgetFormInputText(array(), array('maxlength' => 30, 'size' => 30)),
      'taggable_id'    => new sfWidgetFormInputText(array(), array('maxlength' => 20, 'size' => 20, 'class' => 'number')),
    ));

    $this->setValidators(array(
      'id'             => new wfValidatorDoctrineChoice(array('model' => 'Tagging', 'column' => 'id', 'required' => false), array ()),
      'tag_id'         => new wfValidatorDoctrineChoice(array('model' => 'Tag'), array ()),
      'taggable_model' => new wfValidatorString(array('max_length' => 30, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'taggable_id'    => new sfValidatorInteger(array('required' => false), array('invalid' => '%value% is not an integer.', 'max' => '%value% must be at most %max%.', 'min' => '%value% must be at least %min%.')),
    ));

    $this->widgetSchema->setNameFormat('tagging[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'Tagging';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

  }

  protected function doUpdateObject($values)
  {
    parent::doUpdateObject($values);
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);
  }


}