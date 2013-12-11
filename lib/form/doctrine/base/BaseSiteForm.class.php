<?php

/**
 * Site form base class.
 *
 * @method Site getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSiteForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'name'              => new sfWidgetFormInputText(),
      'org_abbr'          => new sfWidgetFormInputText(),
      'org_name'          => new sfWidgetFormInputText(),
      'theme'             => new sfWidgetFormInputText(),
      'mode'              => new sfWidgetFormChoice(array('choices' => array('listing' => 'listing', 'entry' => 'entry'))),
      'domain'            => new sfWidgetFormInputText(),
      'logo_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Logo'), 'add_empty' => true)),
      'location_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'tag_headings_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'TagHeading')),
      'disciplines_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Discipline')),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'              => new sfValidatorString(array('max_length' => 50)),
      'org_abbr'          => new sfValidatorString(array('max_length' => 20)),
      'org_name'          => new sfValidatorString(array('max_length' => 60)),
      'theme'             => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'mode'              => new sfValidatorChoice(array('choices' => array(0 => 'listing', 1 => 'entry'), 'required' => false)),
      'domain'            => new sfValidatorString(array('max_length' => 255)),
      'logo_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Logo'), 'required' => false)),
      'location_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
      'tag_headings_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'TagHeading', 'required' => false)),
      'disciplines_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Discipline', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Site', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('site[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Site';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['tag_headings_list']))
    {
      $this->setDefault('tag_headings_list', $this->object->TagHeadings->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['disciplines_list']))
    {
      $this->setDefault('disciplines_list', $this->object->Disciplines->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveTagHeadingsList($con);
    $this->saveDisciplinesList($con);

    parent::doSave($con);
  }

  public function saveTagHeadingsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['tag_headings_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->TagHeadings->getPrimaryKeys();
    $values = $this->getValue('tag_headings_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('TagHeadings', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('TagHeadings', array_values($link));
    }
  }

  public function saveDisciplinesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['disciplines_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Disciplines->getPrimaryKeys();
    $values = $this->getValue('disciplines_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Disciplines', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Disciplines', array_values($link));
    }
  }

}
