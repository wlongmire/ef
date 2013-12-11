<?php

/**
 * Event form base class.
 *
 * @method Event getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseEventForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'venue_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Venue'), 'add_empty' => true)),
      'event_type_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('EventType'), 'add_empty' => false)),
      'media_item_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Picture'), 'add_empty' => true)),
      'name'                 => new sfWidgetFormInputText(),
      'blurb'                => new sfWidgetFormInputText(),
      'min_cost'             => new sfWidgetFormInputText(),
      'max_cost'             => new sfWidgetFormInputText(),
      'url'                  => new sfWidgetFormInputText(),
      'ticket_url'           => new sfWidgetFormInputText(),
      'is_published'         => new sfWidgetFormInputCheckbox(),
      'suggested_venue_name' => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'profiles_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Profile')),
      'disciplines_list'     => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Discipline')),
      'owners_list'          => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'venue_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Venue'), 'required' => false)),
      'event_type_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('EventType'))),
      'media_item_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Picture'), 'required' => false)),
      'name'                 => new sfValidatorString(array('max_length' => 100)),
      'blurb'                => new sfValidatorPass(array('required' => false)),
      'min_cost'             => new sfValidatorNumber(array('required' => false)),
      'max_cost'             => new sfValidatorNumber(array('required' => false)),
      'url'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ticket_url'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_published'         => new sfValidatorBoolean(array('required' => false)),
      'suggested_venue_name' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
      'profiles_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Profile', 'required' => false)),
      'disciplines_list'     => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Discipline', 'required' => false)),
      'owners_list'          => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Event';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['profiles_list']))
    {
      $this->setDefault('profiles_list', $this->object->Profiles->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['disciplines_list']))
    {
      $this->setDefault('disciplines_list', $this->object->Disciplines->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['owners_list']))
    {
      $this->setDefault('owners_list', $this->object->Owners->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveProfilesList($con);
    $this->saveDisciplinesList($con);
    $this->saveOwnersList($con);

    parent::doSave($con);
  }

  public function saveProfilesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['profiles_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Profiles->getPrimaryKeys();
    $values = $this->getValue('profiles_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Profiles', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Profiles', array_values($link));
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

  public function saveOwnersList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['owners_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Owners->getPrimaryKeys();
    $values = $this->getValue('owners_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Owners', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Owners', array_values($link));
    }
  }

}
