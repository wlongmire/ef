<?php
abstract class wfFormDoctrine extends sfFormDoctrine
{  
  public function setup()
  {
    $formFormatter = $this->getWidgetSchema()->getFormFormatter();
    if (method_exists($formFormatter, 'setDecoratorClass'))
    {
      $formFormatter->setDecoratorClass(str_replace('_', '-', wfToolkit::underscore(get_class($this->object)) . '-form'));
    }
  }

  /**
   * @param array $columns
   * @param sfValidatorBase $validator
   * @return sfValidatorDoctrineUnique
   */
  public function getPostValidatorUnique($columns, $validator = null)
  {
    if ($validator === null)
    {
      $validator = $this->getValidatorSchema()->getPostValidator();
    }
    if ($validator instanceof sfValidatorDoctrineUnique)
    {
      if (!array_diff($validator->getOption('column'), $columns))
      {
        return $validator;
      }
    }
    elseif (method_exists($validator, 'getValidators'))
    {
      foreach($validator->getValidators() as $childValidator)
      {
        if ($matchingValidator = $this->getPostValidatorUnique($columns, $childValidator))
        {
          return $matchingValidator;
        }
      }
    }
    return null;
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
    }

    if (null === $forms)
    {
      $forms = $this->getEmbeddedFormsToSave();
    }

    foreach ($forms as $name => $form)
    {
      if ($form instanceof sfFormObject)
      {
        $form->saveEmbeddedForms($con);
        $form->getObject()->save($con);
      }
      elseif ($form instanceof wfFormSymfonySavable)
      {
        $form->doSave($this->getValue($name));
      }
      else
      {
        $formsToSave = method_exists($form, 'getEmbeddedFormsToSave') ? $form->getEmbeddedFormsToSave() : $form->getEmbeddedForms();
        $this->saveEmbeddedForms($con, $formsToSave);
      }
    }
  }

  public function updateObjectEmbeddedForms($values, $forms = null)
  {
    if (null === $forms)
    {
      $forms = $this->getEmbeddedFormsToSave();
    }

    foreach ($forms as $name => $form)
    {
      if (!isset($values[$name]) || !is_array($values[$name]))
      {
        continue;
      }

      if ($form instanceof sfFormObject)
      {
        $form->updateObject($values[$name]);
      }
      else
      {
        $formsToSave = $form instanceof wfFormDoctrine ? $form->getEmbeddedFormsToSave() : $form->getEmbeddedForms();
        $this->updateObjectEmbeddedForms($values[$name], $formsToSave);
      }
    }
  }


  /**
   * @return Doctrine_Table
   */
  public function getTable()
  {
    return $this->getObject()->getTable();
  }
}