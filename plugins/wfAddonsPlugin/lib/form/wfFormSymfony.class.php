<?php

/**
 * Base form methods that we want to exist in all projects
 */
class wfFormSymfony extends sfFormSymfony
{
  static protected $javascriptValues = array();
  protected $formsToSkip = array(); //an array of embedded form keys to skip saving - not used directly by this class
  
  /**
   * @param string $name The name of the embedded form
   * @return boolean True if embedded form $name exists, false otherwise
   */
  public function hasEmbeddedForm($name)
  {
    return isset($this->embeddedForms[$name]);
  }

  /**
  * @param string $name Adds $name to an array of form names to ignore when saving/updating.
  */
  protected function skipSavingForm($name)
  {
    $this->formsToSkip[$name] = $name;
    $this->validatorSchema[$name] = new sfValidatorPass();
  }

  /**
  * @return array An array of forms to be saved
  */
  public function getEmbeddedFormsToSave()
  {
    return array_diff_key($this->getEmbeddedForms(), $this->formsToSkip);
  }

  /**
   * @return boolean True if the form has errors and does not have global errors, false otherwise
   */
  public function hasOnlyLocalErrors()
  {
    return $this->hasErrors() && !$this->hasGlobalErrors();
  }

  /**
   * @param mixed $fields - This function can receive either an array of widgets or a list of widget strings
   * * if $fields is not provided, it will be disabled on all widgets
   * Sets autocomplete attribute on widgets corresponding to provided fields to "off"
   */
  public function disableAutocomplete()
  {
    $fields = func_get_args();
    if (isset($fields[0]) && is_array($fields[0]))
    {
      $fields = $fields[0];
    }
    if (!$fields)
    {
      $fields = array_keys($this->widgetSchema->getFields());
    }

    foreach($fields as $name)
    {
      $this->getWidget($name)->setAttribute('autocomplete', 'off');
    }
  }

  public function printDebugInfo()
  {
    if (sfConfig::get('sf_environment') != 'dev')
    {
      return;
    }
    foreach($this->getErrorSchema()->getErrors() as $key => $error)
    {
      echo '<p>' . $key . ': ' . $error . '</p>';
    }
  }

  /**
   * Overridden getJavascripts to include javascripts for embedded forms
   * @return array
   */
  public function getJavascripts()
  {
    $javascripts = array_merge(parent::getJavaScripts(), $this->getEmbeddedFormAssets('javascript'));
    return array_unique($javascripts);
  }

  /**
   * Overridden getJavascripts to include javascripts for embedded forms
   * @return array
   */
  public function getStylesheets()
  {
    $stylesheets = array_merge(parent::getStylesheets(), $this->getEmbeddedFormAssets('stylesheet'));
    return $stylesheets;
  }


  public function getEmbeddedFormAssets($type = null, $forms = null)
  {
    if (!in_array($type, array('javascript', 'stylesheet')))
    {
      return array();
    }
    
    if (null === $forms)
    {
      $forms = $this->embeddedForms;
    }

    $method = $type == 'javascript' ? 'getJavascripts' : 'getStylesheets';
    $assets = array();
    foreach ($forms as $form)
    {
      $assets = array_merge($assets, $form->$method());
      if (!$form instanceof wfFormSymfony)
      {
        $assets = array_merge($assets, $this->getEmbeddedFormAssets($type, $form->getEmbeddedForms()));
      }
    }

    return $assets;
  }

  /**
   * Adds a value that should be a global JS value
   * @param string $key
   * @param mixed $value Anything that can be passed to json_encode
   */
  public static function addJavascriptValue($key, $value)
  {
    self::$javascriptValues[$key] = $value;
  }

  /**
   * Adds an array of global JS values
   * @param array $values An array of key => value pairs
   */
  public static function addJavascriptValues($values)
  {
    self::$javascriptValues = array_merge(self::$javascriptValues, $values);
  }

  /**
   * All global JS values
   * @return array
   */
  public static function getJavascriptValues()
  {
    return self::$javascriptValues;
  }

  public function addCSRFProtection($secret = null)
  {
    parent::addCSRFProtection($secret);
    if (isset($this->validatorSchema[self::$CSRFFieldName]))
    {
      $this->validatorSchema[self::$CSRFFieldName] = new wfValidatorCSRFToken(array(
          'token' => $this->validatorSchema[self::$CSRFFieldName]->getOption('token')
      ));
    }
    return $this;
  }

  /**
   * Merges $value with the default value of $name. The default value and the new value must both be arrays.
   * @param string $name
   * @param array $value
   * @return wfFormSymfony $this
   */
  public function mergeDefault($name, $value)
  {
    $default = $this->getDefault($name);
    if (!is_array($default))
    {
      throw new InvalidArgumentException('The default value for ' . $name . ' must be an array.');
    }
    if (!is_array($value))
    {
      throw new InvalidArgumentException('The value to merge must be an array.');
    }
    return $this->setDefault($name, sfToolkit::arrayDeepMerge($default, $value));
  }
}
