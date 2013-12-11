<?php

/**
 * VenueVenueType form base class.
 *
 * @method VenueVenueType getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseVenueVenueTypeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'venue_id'      => new sfWidgetFormInputHidden(),
      'venue_type_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'venue_id'      => new sfValidatorChoice(array('choices' => array($this->getObject()->get('venue_id')), 'empty_value' => $this->getObject()->get('venue_id'), 'required' => false)),
      'venue_type_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('venue_type_id')), 'empty_value' => $this->getObject()->get('venue_type_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('venue_venue_type[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'VenueVenueType';
  }

}
