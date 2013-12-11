<?php

/**
 * aBlogEditor form base class.
 *
 * @package    eventsfilter
 * @subpackage a_blog_editor * @author     Jeremy Kauffman
 */
abstract class BaseaBlogEditorForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'blog_item_id' => new sfWidgetFormInputHidden(array(), array()),
      'user_id'      => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'blog_item_id' => new wfValidatorDoctrineChoice(array('model' => 'aBlogEditor', 'column' => 'blog_item_id', 'required' => false), array ()),
      'user_id'      => new wfValidatorDoctrineChoice(array('model' => 'aBlogEditor', 'column' => 'user_id', 'required' => false), array ()),
    ));

    $this->widgetSchema->setNameFormat('a_blog_editor[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aBlogEditor';
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