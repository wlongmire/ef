<?php

/**
 * aCategory form base class.
 *
 * @package    eventsfilter
 * @subpackage a_category * @author     Jeremy Kauffman
 */
abstract class BaseaCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(array(), array()),
      'name'             => new wfWidgetFormJqueryTextarea(array(), array()),
      'description'      => new sfWidgetFormInputText(array(), array('size' => 25)),
      'created_at'       => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'updated_at'       => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'slug'             => new wfWidgetFormJqueryTextarea(array(), array()),
      'media_items_list' => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aMediaItem')),
      'pages_list'       => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aPage')),
      'users_list'       => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'groups_list'      => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'blog_items_list'  => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem')),
    ));

    $this->setValidators(array(
      'id'               => new wfValidatorDoctrineChoice(array('model' => 'aCategory', 'column' => 'id', 'required' => false), array ()),
      'name'             => new wfValidatorString(array('max_length' => 255, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'description'      => new wfValidatorString(array('required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'created_at'       => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'updated_at'       => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'slug'             => new wfValidatorString(array('max_length' => 255, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'media_items_list' => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aMediaItem', 'required' => false)),
      'pages_list'       => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aPage', 'required' => false)),
      'users_list'       => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'groups_list'      => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'blog_items_list'  => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new wfValidatorDoctrineUnique(array('model' => 'aCategory', 'column' => array(0 => 'name'), 'object' => $this->object), array ()),
        new wfValidatorDoctrineUnique(array('model' => 'aCategory', 'column' => array(0 => 'slug'), 'object' => $this->object), array ()),
      ))
    );

    $this->widgetSchema->setNameFormat('a_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aCategory';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['media_items_list']))
    {
      $this->setDefault('media_items_list', $this->object->MediaItems->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['pages_list']))
    {
      $this->setDefault('pages_list', $this->object->Pages->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['users_list']))
    {
      $this->setDefault('users_list', $this->object->Users->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['groups_list']))
    {
      $this->setDefault('groups_list', $this->object->Groups->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['blog_items_list']))
    {
      $this->setDefault('blog_items_list', $this->object->BlogItems->getPrimaryKeys());
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
    if (isset($this->widgetSchema['media_items_list']))
    {
      $this->saveMediaItemsList($con, isset($values['media_items_list']) ? $values['media_items_list'] : null);
    }
    if (isset($this->widgetSchema['pages_list']))
    {
      $this->savePagesList($con, isset($values['pages_list']) ? $values['pages_list'] : null);
    }
    if (isset($this->widgetSchema['users_list']))
    {
      $this->saveUsersList($con, isset($values['users_list']) ? $values['users_list'] : null);
    }
    if (isset($this->widgetSchema['groups_list']))
    {
      $this->saveGroupsList($con, isset($values['groups_list']) ? $values['groups_list'] : null);
    }
    if (isset($this->widgetSchema['blog_items_list']))
    {
      $this->saveBlogItemsList($con, isset($values['blog_items_list']) ? $values['blog_items_list'] : null);
    }
  }

  protected function saveMediaItemsList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->MediaItems->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('MediaItems', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('MediaItems', array_values($link));
    }
  }

  protected function savePagesList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->Pages->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Pages', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Pages', array_values($link));
    }
  }

  protected function saveUsersList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->Users->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Users', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Users', array_values($link));
    }
  }

  protected function saveGroupsList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->Groups->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Groups', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Groups', array_values($link));
    }
  }

  protected function saveBlogItemsList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->BlogItems->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('BlogItems', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('BlogItems', array_values($link));
    }
  }

}