<?php

/**
 * aCategoryGroup form base class.
 *
 * @package    eventsfilter
 * @subpackage a_category_group * @author     Jeremy Kauffman
 */
abstract class BaseaCategoryGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'category_id' => new sfWidgetFormInputHidden(array(), array()),
      'group_id'    => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'category_id' => new wfValidatorDoctrineChoice(array('model' => 'aCategoryGroup', 'column' => 'category_id', 'required' => false), array ()),
      'group_id'    => new wfValidatorDoctrineChoice(array('model' => 'aCategoryGroup', 'column' => 'group_id', 'required' => false), array ()),
    ));

    $this->widgetSchema->setNameFormat('a_category_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aCategoryGroup';
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