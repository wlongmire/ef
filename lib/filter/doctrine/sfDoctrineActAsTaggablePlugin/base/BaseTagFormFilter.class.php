<?php

/**
 * Tag filter form base class.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseTagFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'             => new sfWidgetFormFilterInput(),
      'is_triple'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'triple_namespace' => new sfWidgetFormFilterInput(),
      'triple_key'       => new sfWidgetFormFilterInput(),
      'triple_value'     => new sfWidgetFormFilterInput(),
      'tag_heading_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TagHeading'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'             => new sfValidatorPass(array('required' => false)),
      'is_triple'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'triple_namespace' => new sfValidatorPass(array('required' => false)),
      'triple_key'       => new sfValidatorPass(array('required' => false)),
      'triple_value'     => new sfValidatorPass(array('required' => false)),
      'tag_heading_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TagHeading'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('tag_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Tag';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'name'             => 'Text',
      'is_triple'        => 'Boolean',
      'triple_namespace' => 'Text',
      'triple_key'       => 'Text',
      'triple_value'     => 'Text',
      'tag_heading_id'   => 'ForeignKey',
    );
  }
}
