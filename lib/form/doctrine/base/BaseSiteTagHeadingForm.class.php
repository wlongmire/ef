<?php

/**
 * SiteTagHeading form base class.
 *
 * @method SiteTagHeading getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseSiteTagHeadingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'site_id'        => new sfWidgetFormInputHidden(),
      'tag_heading_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'site_id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('site_id')), 'empty_value' => $this->getObject()->get('site_id'), 'required' => false)),
      'tag_heading_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('tag_heading_id')), 'empty_value' => $this->getObject()->get('tag_heading_id'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('site_tag_heading[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SiteTagHeading';
  }

}
