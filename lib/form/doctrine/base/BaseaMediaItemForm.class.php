<?php

/**
 * aMediaItem form base class.
 *
 * @package    eventsfilter
 * @subpackage a_media_item * @author     Jeremy Kauffman
 */
abstract class BaseaMediaItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(array(), array()),
      'lucene_dirty'    => new sfWidgetFormInputCheckbox(array(), array()),
      'type'            => new sfWidgetFormChoice(array('choices' => array('image' => 'image', 'video' => 'video', 'audio' => 'audio', 'pdf' => 'pdf'), 'expanded' => true), array()),
      'service_url'     => new wfWidgetFormJqueryTextarea(array(), array()),
      'format'          => new sfWidgetFormInputText(array(), array('maxlength' => 10, 'size' => 10)),
      'width'           => new sfWidgetFormInputText(array(), array('maxlength' => 20, 'size' => 20, 'class' => 'number')),
      'height'          => new sfWidgetFormInputText(array(), array('maxlength' => 20, 'size' => 20, 'class' => 'number')),
      'embed'           => new wfWidgetFormJqueryTextarea(array(), array()),
      'title'           => new wfWidgetFormJqueryTextarea(array(), array('class' => 'required')),
      'description'     => new sfWidgetFormInputText(array(), array('size' => 25)),
      'credit'          => new wfWidgetFormJqueryTextarea(array(), array()),
      'owner_id'        => new wfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Owner'), 'add_empty' => true), array()),
      'view_is_secure'  => new sfWidgetFormInputCheckbox(array(), array()),
      'created_at'      => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'updated_at'      => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'slug'            => new wfWidgetFormJqueryTextarea(array(), array()),
      'slots_list'      => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aSlot')),
      'categories_list' => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'id'              => new wfValidatorDoctrineChoice(array('model' => 'aMediaItem', 'column' => 'id', 'required' => false), array ()),
      'lucene_dirty'    => new sfValidatorBoolean(array('required' => false), array ()),
      'type'            => new sfValidatorChoice(array('choices' => array('image' => 'image', 'video' => 'video', 'audio' => 'audio', 'pdf' => 'pdf')), array ()),
      'service_url'     => new wfValidatorString(array('max_length' => 200, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'format'          => new wfValidatorString(array('max_length' => 10, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'width'           => new sfValidatorInteger(array('required' => false), array('invalid' => '%value% is not an integer.', 'max' => '%value% must be at most %max%.', 'min' => '%value% must be at least %min%.')),
      'height'          => new sfValidatorInteger(array('required' => false), array('invalid' => '%value% is not an integer.', 'max' => '%value% must be at most %max%.', 'min' => '%value% must be at least %min%.')),
      'embed'           => new wfValidatorString(array('max_length' => 1000, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'title'           => new wfValidatorString(array('max_length' => 200), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'description'     => new wfValidatorString(array('required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'credit'          => new wfValidatorString(array('max_length' => 200, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'owner_id'        => new wfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'required' => false), array ()),
      'view_is_secure'  => new sfValidatorBoolean(array(), array ()),
      'created_at'      => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'updated_at'      => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'slug'            => new wfValidatorString(array('max_length' => 255, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'slots_list'      => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aSlot', 'required' => false)),
      'categories_list' => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
       new wfValidatorDoctrineUnique(array('model' => 'aMediaItem', 'column' => array(0 => 'slug'), 'object' => $this->object), array ())
    );

    $this->widgetSchema->setNameFormat('a_media_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aMediaItem';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['slots_list']))
    {
      $this->setDefault('slots_list', $this->object->Slots->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['categories_list']))
    {
      $this->setDefault('categories_list', $this->object->Categories->getPrimaryKeys());
    }

  }

  protected function doUpdateObject($values)
  {
    parent::doUpdateObject($values);
  }

  protected function doSave($con = null)
  {
    $this->saveManyToMany($con);
    parent::doSave($con);
  }

  public function saveManyToMany($con = null, $values = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
    }
    if ($values === null)
    {
      $values = $this->getValues();
    }
    if (isset($this->widgetSchema['slots_list']))
    {
      $this->saveSlotsList($con, isset($values['slots_list']) ? $values['slots_list'] : null);
    }
    if (isset($this->widgetSchema['categories_list']))
    {
      $this->saveCategoriesList($con, isset($values['categories_list']) ? $values['categories_list'] : null);
    }
  }

  protected function saveSlotsList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->Slots->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Slots', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Slots', array_values($link));
    }
  }

  protected function saveCategoriesList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->Categories->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Categories', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Categories', array_values($link));
    }
  }

}