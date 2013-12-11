<?php
/**
 * aEmbedMediaAccount filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseaEmbedMediaAccountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'service'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'service'  => new sfValidatorPass(array('required' => false)),
      'username' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('a_embed_media_account_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'aEmbedMediaAccount';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'service'  => 'Text',
      'username' => 'Text',
    );
  }

}