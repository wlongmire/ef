<?php

/**
 * aRedirect form base class.
 *
 * @package    eventsfilter
 * @subpackage a_redirect * @author     Jeremy Kauffman
 */
abstract class BaseaRedirectForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(array(), array()),
      'page_id'    => new wfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true), array()),
      'slug'       => new wfWidgetFormJqueryTextarea(array(), array()),
      'created_at' => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'updated_at' => new sfWidgetFormDateTime(array(), array('class' => 'required')),
    ));

    $this->setValidators(array(
      'id'         => new wfValidatorDoctrineChoice(array('model' => 'aRedirect', 'column' => 'id', 'required' => false), array ()),
      'page_id'    => new wfValidatorDoctrineChoice(array('model' => 'aPage', 'required' => false), array ()),
      'slug'       => new wfValidatorString(array('max_length' => 255, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'created_at' => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'updated_at' => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
    ));

    $this->validatorSchema->setPostValidator(
       new wfValidatorDoctrineUnique(array('model' => 'aRedirect', 'column' => array(0 => 'slug'), 'object' => $this->object), array ())
    );

    $this->widgetSchema->setNameFormat('a_redirect[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aRedirect';
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