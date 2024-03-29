<?php

/**
 * VenueOwner form base class.
 *
 * @method VenueOwner getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseVenueOwnerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'venue_id'   => new sfWidgetFormInputHidden(),
      'user_id'    => new sfWidgetFormInputHidden(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'venue_id'   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('venue_id')), 'empty_value' => $this->getObject()->get('venue_id'), 'required' => false)),
      'user_id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_id')), 'empty_value' => $this->getObject()->get('user_id'), 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('venue_owner[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'VenueOwner';
  }

}
