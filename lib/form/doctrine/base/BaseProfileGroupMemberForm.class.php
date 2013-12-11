<?php

/**
 * ProfileGroupMember form base class.
 *
 * @method ProfileGroupMember getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProfileGroupMemberForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'group_profile_id'  => new sfWidgetFormInputHidden(),
      'member_profile_id' => new sfWidgetFormInputHidden(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'group_profile_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('group_profile_id')), 'empty_value' => $this->getObject()->get('group_profile_id'), 'required' => false)),
      'member_profile_id' => new sfValidatorChoice(array('choices' => array($this->getObject()->get('member_profile_id')), 'empty_value' => $this->getObject()->get('member_profile_id'), 'required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('profile_group_member[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileGroupMember';
  }

}
