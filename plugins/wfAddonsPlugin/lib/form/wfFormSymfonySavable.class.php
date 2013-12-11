<?php
abstract class wfFormSymfonySavable extends BaseForm
{
  /**
   * Binds the current form and saves the object to the database in one step.
   *
   * @param  array An array of tainted values to use to bind the form
   * @param  array An array of uploaded files (in the $_FILES or $_GET format)
   * @param  mixed An optional connection object
   *
   * @return Boolean true if the form is valid, false otherwise
   */
  public function bindAndSave($taintedValues, $taintedFiles = null, $con = null)
  {
    $this->bind($taintedValues, $taintedFiles);
    if ($this->isValid())
    {
      $con = $this->getOption('use_transaction', true) ? Doctrine_Manager::connection() : null;
      $this->save($this->values, $con);
      return true;
    }

    return false;
  }

  /**
   * Saves the current form.
   *
   * @param array|null $values The values to use when saving. If null, $this->values will be used.
   * @param Doctrine_Connection|null $con An optional connection object. Save will ONLY be wrapped in a transaction if $con is provided
   *
   * @see doSave()
   *
   * @throws sfValidatorError If the form is not valid
   */
  public function save($values = null, Doctrine_Connection $con = null)
  {    
    try
    {
      if ($con)
      {
        $con->beginTransaction();
      }

      if ($values === null)
      {
        $values = $this->values;
      }
      
      $this->doSave($values);
      $this->saveEmbeddedForms($con, $values, $this->getEmbeddedFormsToSave());

      if ($con)
      {
        $con->commit();
      }
    }
    catch (Exception $e)
    {
      if ($con)
      {
        $con->rollBack();
      }

      throw $e;
    }
  }

  protected function saveEmbeddedForms(Doctrine_Connection $con = null, $values = array(), $forms = array())
  {
    foreach ($forms as $name => $form)
    {
      $formValues = isset($values[$name]) ? $values[$name] : array();
      if (method_exists($form, 'updateObject'))
      {
        $form->updateObject($formValues);
      }
      if ($form instanceof wfFormSymfonySavable)
      {
        $form->doSave($values[$name]);
      }
      elseif ($form instanceof sfFormObject)
      {
        if (method_exists($form, 'saveManyToMany'))
        {
          $form->saveManyToMany($con, $values[$name]);
        }
        $form->getObject()->save($con);
      }
      $formsToSave = method_exists($form, 'getEmbeddedFormsToSave') ? $form->getEmbeddedFormsToSave() : $form->getEmbeddedForms();
      $this->saveEmbeddedForms($con, $formValues, $formsToSave);
    }
  }

  /**
   * Save logic goes here
   */
  abstract protected function doSave($values);
}
