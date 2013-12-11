<?php

/**
 * Tag form base class.
 *
 * @package    eventsfilter
 * @subpackage tag * @author     Jeremy Kauffman
 */
abstract class BaseTagForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(array(), array()),
      'name'             => new sfWidgetFormInputText(array(), array('maxlength' => 100, 'size' => 40)),
      'is_triple'        => new sfWidgetFormInputCheckbox(array(), array()),
      'triple_namespace' => new sfWidgetFormInputText(array(), array('maxlength' => 100, 'size' => 40)),
      'triple_key'       => new sfWidgetFormInputText(array(), array('maxlength' => 100, 'size' => 40)),
      'triple_value'     => new sfWidgetFormInputText(array(), array('maxlength' => 100, 'size' => 40)),
    ));

    $this->setValidators(array(
      'id'               => new wfValidatorDoctrineChoice(array('model' => 'Tag', 'column' => 'id', 'required' => false), array ()),
      'name'             => new wfValidatorString(array('max_length' => 100, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'is_triple'        => new sfValidatorBoolean(array('required' => false), array ()),
      'triple_namespace' => new wfValidatorString(array('max_length' => 100, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'triple_key'       => new wfValidatorString(array('max_length' => 100, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'triple_value'     => new wfValidatorString(array('max_length' => 100, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
    ));

    $this->widgetSchema->setNameFormat('tag[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'Tag';
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