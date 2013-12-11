<?php

/**
 * aBlogItem form base class.
 *
 * @package    eventsfilter
 * @subpackage a_blog_item * @author     Jeremy Kauffman
 */
abstract class BaseaBlogItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(array(), array()),
      'author_id'       => new wfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Author'), 'add_empty' => true), array()),
      'page_id'         => new wfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'add_empty' => true), array()),
      'title'           => new wfWidgetFormJqueryTextarea(array(), array('class' => 'required')),
      'slug'            => new sfWidgetFormInputText(array(), array()),
      'slug_saved'      => new sfWidgetFormInputCheckbox(array(), array()),
      'excerpt'         => new sfWidgetFormInputText(array(), array('size' => 25)),
      'status'          => new sfWidgetFormChoice(array('choices' => array('draft' => 'draft', 'pending review' => 'pending review', 'published' => 'published'), 'expanded' => true), array()),
      'allow_comments'  => new sfWidgetFormInputCheckbox(array(), array()),
      'template'        => new wfWidgetFormJqueryTextarea(array(), array()),
      'published_at'    => new sfWidgetFormDateTime(array(), array()),
      'type'            => new wfWidgetFormJqueryTextarea(array(), array()),
      'start_date'      => new wfWidgetFormJqueryDate(array(), array('maxlength' => 10, 'size' => 10, 'class' => 'date')),
      'start_time'      => new wfWidgetFormTime(array(), array()),
      'end_date'        => new wfWidgetFormJqueryDate(array(), array('maxlength' => 10, 'size' => 10, 'class' => 'date')),
      'end_time'        => new wfWidgetFormTime(array(), array()),
      'location'        => new wfWidgetFormJqueryTextarea(array(), array()),
      'created_at'      => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'updated_at'      => new sfWidgetFormDateTime(array(), array('class' => 'required')),
      'editors_list'    => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
      'categories_list' => new wfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
    ));

    $this->setValidators(array(
      'id'              => new wfValidatorDoctrineChoice(array('model' => 'aBlogItem', 'column' => 'id', 'required' => false), array ()),
      'author_id'       => new wfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'required' => false), array ()),
      'page_id'         => new wfValidatorDoctrineChoice(array('model' => 'aPage', 'required' => false), array ()),
      'title'           => new wfValidatorString(array('max_length' => 255), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'slug'            => new sfValidatorPass(array(), array ()),
      'slug_saved'      => new sfValidatorBoolean(array('required' => false), array ()),
      'excerpt'         => new wfValidatorString(array('required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'status'          => new sfValidatorChoice(array('choices' => array('draft' => 'draft', 'pending review' => 'pending review', 'published' => 'published')), array ()),
      'allow_comments'  => new sfValidatorBoolean(array(), array ()),
      'template'        => new wfValidatorString(array('max_length' => 255, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'published_at'    => new wfValidatorDate(array('required' => false), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'type'            => new wfValidatorString(array('max_length' => 255, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'start_date'      => new wfValidatorDate(array('required' => false), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'start_time'      => new wfValidatorTime(array('required' => false), array('max' => 'Must be at or before %max%.', 'min' => 'Must be at or after %min%.')),
      'end_date'        => new wfValidatorDate(array('required' => false), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'end_time'        => new wfValidatorTime(array('required' => false), array('max' => 'Must be at or before %max%.', 'min' => 'Must be at or after %min%.')),
      'location'        => new wfValidatorString(array('max_length' => 300, 'required' => false), array('max_length' => '%max_length% characters maximum.', 'min_length' => '%min_length% characters minimum.')),
      'created_at'      => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'updated_at'      => new wfValidatorDate(array(), array('max' => 'Must be before %max%.', 'min' => 'Must be after %min%.')),
      'editors_list'    => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
      'categories_list' => new wfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();


    parent::setup();
  }

  public function getModelName()
  {
    return 'aBlogItem';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['editors_list']))
    {
      $this->setDefault('editors_list', $this->object->Editors->getPrimaryKeys());
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
    if (isset($this->widgetSchema['editors_list']))
    {
      $this->saveEditorsList($con, isset($values['editors_list']) ? $values['editors_list'] : null);
    }
    if (isset($this->widgetSchema['categories_list']))
    {
      $this->saveCategoriesList($con, isset($values['categories_list']) ? $values['categories_list'] : null);
    }
  }

  protected function saveEditorsList(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object->Editors->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Editors', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Editors', array_values($link));
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