<?php

/**
 * aPage form base class.
 *
 * @package    eventsfilter
 * @subpackage a_page * @author     Jeremy Kauffman
 */
abstract class BaseaPageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(array(), array()),
      'slug'            => new wfWidgetFormJqueryTextarea(array(), array()),
      'template'        => new sfWidgetFormInputText(array(), array('maxlength' => 100, 'size' => 40)),
      'view_is_secure'  => new sfWidgetFormInputCheckbox(array(), array()),
      'view_guest'      => new sfWidgetFormInputCheckbox(array(), array()),
      'edit_admin_lock' => new sfWidgetFormInputCheckbox(array(), array()),
      'view_admin_lock' => new sfWidgetFormInputCheckbox(array(), array()),
      'published_at'    => new sfWidgetFormDateTime(array(), array()),
      'archived'        => new sfWidgetFormInputCheckbox(array(), array()),
      'admin'           => new sfWidgetFormInputCheckbox(array(), array()),
      'author_id'       => new wfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Author'), 'add_empty' => true), array()),
      'deleter_id'      => new wfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Deleter'), 'add_empty' => true), array()),
      'engine'          => new wfWidgetFormJqueryTextarea(array(), array()),
      'created_at'      => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'updated_at'      => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'lft'             => new sfWidgetFormInputText(array(), array('maxlength' => 12, 'size' => 12, 'class' => 'number')),
      'rgt'             => new sfWidgetFormInputText(array(), array('maxlength' => 12, 'size' => 12, 'class' => 'number')),
      'level'           => new sfWidgetFormInputText(array(), array('maxlength' => 6, 'size' => 6, 'class' => 'number')),
      'categories_list' => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'id'              => new wfValidatorDoctrineChoice(array('model' => 'aPage', 'column' => 'id', 'required' => false), array ()),
      'slug'            => new wfValidatorString(array('max_length' => 1000, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'template'        => new wfValidatorString(array('max_length' => 100, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'view_is_secure'  => new sfValidatorBoolean(array('required' => false), array ()),
      'view_guest'      => new sfValidatorBoolean(array('required' => false), array ()),
      'edit_admin_lock' => new sfValidatorBoolean(array('required' => false), array ()),
      'view_admin_lock' => new sfValidatorBoolean(array('required' => false), array ()),
      'published_at'    => new wfValidatorDate(array('required' => false), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'archived'        => new sfValidatorBoolean(array('required' => false), array ()),
      'admin'           => new sfValidatorBoolean(array('required' => false), array ()),
      'author_id'       => new wfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'required' => false), array ()),
      'deleter_id'      => new wfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'required' => false), array ()),
      'engine'          => new wfValidatorString(array('max_length' => 255, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'created_at'      => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'updated_at'      => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'lft'             => new sfValidatorInteger(array('required' => false), array('invalid' => '%value% is not an integer.', 'max' => '%value% must be at most %max%.', 'min' => '%value% must be at least %min%.')),
      'rgt'             => new sfValidatorInteger(array('required' => false), array('invalid' => '%value% is not an integer.', 'max' => '%value% must be at most %max%.', 'min' => '%value% must be at least %min%.')),
      'level'           => new sfValidatorInteger(array('required' => false), array('invalid' => '%value% is not an integer.', 'max' => '%value% must be at most %max%.', 'min' => '%value% must be at least %min%.')),
      'categories_list' => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_page[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aPage';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

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
    if (isset($this->widgetSchema['categories_list']))
    {
      $this->saveCategoriesList($con, isset($values['categories_list']) ? $values['categories_list'] : null);
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