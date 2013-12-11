<?php

/**
 * Venue form base class.
 *
 * @method Venue getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseVenueForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'name'             => new sfWidgetFormInputText(),
      'blurb'            => new sfWidgetFormInputText(),
      'location_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Location'), 'add_empty' => false)),
      'media_item_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Picture'), 'add_empty' => true)),
      'url'              => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
      'address_1'        => new sfWidgetFormInputText(),
      'address_2'        => new sfWidgetFormInputText(),
      'city'             => new sfWidgetFormInputText(),
      'state'            => new sfWidgetFormInputText(),
      'zip_code'         => new sfWidgetFormInputText(),
      'venue_types_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'VenueType')),
      'owners_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'             => new sfValidatorString(array('max_length' => 100)),
      'blurb'            => new sfValidatorPass(array('required' => false)),
      'location_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Location'))),
      'media_item_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Picture'), 'required' => false)),
      'url'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
      'address_1'        => new sfValidatorString(array('max_length' => 100)),
      'address_2'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'city'             => new sfValidatorString(array('max_length' => 100)),
      'state'            => new sfValidatorRegex(array('max_length' => 2, 'pattern' => '/[A-Za-z]{2}/')),
      'zip_code'         => new sfValidatorRegex(array('max_length' => 6, 'pattern' => '/[A-Za-z0-9]{5,6}/')),
      'venue_types_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'VenueType', 'required' => false)),
      'owners_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('venue[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Venue';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['venue_types_list']))
    {
      $this->setDefault('venue_types_list', $this->object->VenueTypes->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['owners_list']))
    {
      $this->setDefault('owners_list', $this->object->Owners->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveVenueTypesList($con);
    $this->saveOwnersList($con);

    parent::doSave($con);
  }

  public function saveVenueTypesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['venue_types_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->VenueTypes->getPrimaryKeys();
    $values = $this->getValue('venue_types_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('VenueTypes', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('VenueTypes', array_values($link));
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
