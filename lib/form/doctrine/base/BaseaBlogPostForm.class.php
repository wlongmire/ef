<?php

/**
 * aBlogPost form base class.
 *
 * @method aBlogPost getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 */
abstract class BaseaBlogPostForm extends aBlogItemForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();


    $this->widgetSchema->setNameFormat('a_blog_post[%s]');

  }

  public function getModelName()
  {
    return 'aBlogPost';
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
