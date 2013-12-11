<?php

/**
 * aEmbedMediaAccount form base class.
 *
 * @package    eventsfilter
 * @subpackage a_embed_media_account * @author     Jeremy Kauffman
 */
abstract class BaseaEmbedMediaAccountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(array(), array()),
      'service'  => new sfWidgetFormInputText(array(), array('maxlength' => 100, 'size' => 40, 'class' => 'required')),
      'username' => new sfWidgetFormInputText(array(), array('maxlength' => 100, 'size' => 40, 'class' => 'required')),
    ));

    $this->setValidators(array(
      'id'       => new wfValidatorDoctrineChoice(array('model' => 'aEmbedMediaAccount', 'column' => 'id', 'required' => false), array ()),
      'service'  => new wfValidatorString(array('max_length' => 100), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'username' => new wfValidatorString(array('max_length' => 100), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
    ));

    $this->widgetSchema->setNameFormat('a_embed_media_account[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aEmbedMediaAccount';
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