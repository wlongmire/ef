<?php

/**
 * aCategoryUser form base class.
 *
 * @package    eventsfilter
 * @subpackage a_category_user * @author     Jeremy Kauffman
 */
abstract class BaseaCategoryUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'category_id' => new sfWidgetFormInputHidden(array(), array()),
      'user_id'     => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'category_id' => new wfValidatorDoctrineChoice(array('model' => 'aCategoryUser', 'column' => 'category_id', 'required' => false), array ()),
      'user_id'     => new wfValidatorDoctrineChoice(array('model' => 'aCategoryUser', 'column' => 'user_id', 'required' => false), array ()),
    ));

    $this->widgetSchema->setNameFormat('a_category_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aCategoryUser';
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