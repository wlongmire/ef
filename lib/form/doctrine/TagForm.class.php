<?php

/**
 * Tag form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TagForm extends BaseTagForm
{

  /**
   * DOCUMENT ME
   */
  public function setup()
  {
    parent::setup();

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Tag', 'column' => array('name')))
    );

    $this->useFields(array('name'));
  }

  /**
   * DOCUMENT ME
   * @param mixed $values
   */
  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->getValues();
    }
    // Slashes break routes in most server configs. Do NOT force case of tags.
    
    $values['name'] = str_replace('/', '-', isset($values['name']) ? $values['name'] : '');
    parent::updateObject($values);
  }
}
