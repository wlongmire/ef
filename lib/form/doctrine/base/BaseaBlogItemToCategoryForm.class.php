<?php

/**
 * aBlogItemToCategory form base class.
 *
 * @package    eventsfilter
 * @subpackage a_blog_item_to_category * @author     Jeremy Kauffman
 */
abstract class BaseaBlogItemToCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'blog_item_id' => new sfWidgetFormInputHidden(array(), array()),
      'category_id'  => new sfWidgetFormInputHidden(array(), array()),
    ));

    $this->setValidators(array(
      'blog_item_id' => new wfValidatorDoctrineChoice(array('model' => 'aBlogItemToCategory', 'column' => 'blog_item_id', 'required' => false), array ()),
      'category_id'  => new wfValidatorDoctrineChoice(array('model' => 'aBlogItemToCategory', 'column' => 'category_id', 'required' => false), array ()),
    ));

    $this->widgetSchema->setNameFormat('a_blog_item_to_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aBlogItemToCategory';
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