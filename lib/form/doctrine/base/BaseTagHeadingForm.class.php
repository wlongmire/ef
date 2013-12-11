<?php

/**
 * TagHeading form base class.
 *
 * @method TagHeading getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseTagHeadingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'sites_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Site')),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorRegex(array('max_length' => 30, 'pattern' => '/^[a-zA-Z0-9][^|~\`[\]{}\/]*$/', 'required' => false)),
      'sites_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Site', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tag_heading[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TagHeading';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['sites_list']))
    {
      $this->setDefault('sites_list', $this->object->Sites->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveSitesList($con);

    parent::doSave($con);
  }

  public function saveSitesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['sites_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Sites->getPrimaryKeys();
    $values = $this->getValue('sites_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Sites', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Sites', array_values($link));
    }
  }

}
