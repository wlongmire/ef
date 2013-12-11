<?php
/**
 * This class is an extended version of sfTesterForm with primitive support for embedded forms. 
 */
class wfTesterForm extends sfTesterForm
{
  public function getFormField($field)
  {
    $tokens = explode('[', $field);
    $formField = $this->form;
    foreach($tokens as $token)
    {
      $formField = $formField[trim($token, ']')];
    }
    return $formField;
  }
  
  /**
   * Outputs some debug information about the current submitted form.
   */
  public function debug()
  {
    if (is_null($this->form))
    {
      throw new LogicException('no form has been submitted.');
    }

    print $this->tester->error('Form debug');

    print sprintf("Submitted values: %s\n", var_export($this->form->getTaintedValues(), true));
    print sprintf("Errors: %s\n", $this->form->getErrorSchema());

    exit(1);
  }
  
  public function hasErrors($value = true)
  {
    if (is_array($value))
    {
      $tester = $this->begin()->hasErrors(true);
      foreach($value as $field => $error)
      {
        $tester = $this->isError($field, $error);
      }
      return $tester->end();
    }
    else
    {
      return parent::hasErrors($value);
    }
  }

  /**
   * Tests if the submitted form has a specific error.
   *
   * @param  string $field   The field name to check for an error (null for global errors)
   * @param  mixed  $message The error message or the number of errors for the field (optional)
   *
   * @return sfTestFunctionalBase|sfTester
   */
  public function isError($field, $value = true)
  {
    if (is_null($this->form))
    {
      throw new LogicException('no form has been submitted.');
    }

    if (is_null($field))
    {
      $error = new sfValidatorErrorSchema(new sfValidatorPass(), $this->form->getGlobalErrors());
    }
    else
    {
      $error = $this->getFormField($field)->getError();
    }

    if (false === $value)
    {
      $this->tester->ok(!$error || 0 == count($error), sprintf('the submitted form has no "%s" error.', $field));
    }
    else if (true === $value)
    {
      $this->tester->ok($error && count($error) > 0, sprintf('the submitted form has a "%s" error.', $field));
    }
    else if (is_int($value))
    {
      $this->tester->ok($error && count($error) == $value, sprintf('the submitted form has %s "%s" error(s).', $value, $field));
    }
    else if (preg_match('/^(!)?([^a-zA-Z0-9\\\\]).+?\\2[ims]?$/', $value, $match))
    {
      if (!$error)
      {
        $this->tester->fail(sprintf('the submitted form has a "%s" error.', $field));
      }
      else
      {
        if ($match[1] == '!')
        {
          $this->tester->unlike($error->getCode(), substr($value, 1), sprintf('the submitted form has a "%s" error that does not match "%s".', $field, $value));
        }
        else
        {
          $this->tester->like($error->getCode(), $value, sprintf('the submitted form has a "%s" error that matches "%s".', $field, $value));
        }
      }
    }
    else
    {
      if (!$error)
      {
        $this->tester->fail(sprintf('the submitted form has a "%s" error (%s).', $field, $value));
      }
      else
      {
        $this->tester->is($error->getCode(), $value, sprintf('the submitted form has a "%s" error (%s).', $field, $value));
      }
    }

    return $this->getObjectToReturn();
  }
}