<?php
class wfDoctrineFormGeneratorTools
{
  public function __construct($generatorManager, $generator)
  {
    $this->generatorManager = $generatorManager;
    $this->generator = $generator;
  }

  /**
   * @param sfDoctrineColumn $column
   * @return string the name of the special column type or null if the column isn't special
   */
  public function getFormColumnType($column)
  {
    $table = $column->getTable();

    $specialTemplateDefinitions = sfConfig::get('app_form_generator_template_type_map');
    foreach($specialTemplateDefinitions as $templateName => $templateDefinition)
    {
      if ($table->hasTemplate($templateName))
      {
        $columnName = $table->getTemplate($templateName)->{$templateDefinition['method']}();
        if ($column->getName() == $columnName)
        {
          return $templateDefinition['type'];
        }
      }
    }
    if ($column->isPrimaryKey())
    {
      return 'primary_key';
    }
    if ($column->isForeignKey())
    {
      return 'foreign_key';
    }

    $specialDefinitionTypes = sfConfig::get('app_form_generator_definition_key_map');
    foreach($specialDefinitionTypes as $definition => $type)
    {
      if ($column->getDefinitionKey($definition))
      {
        return $type;
      }
    }

    $columnType = $column->getDoctrineType();
    if ($columnType == 'string')
    {
      $columnType =  $column->getLength() > sfConfig::get('app_form_generator_long_string_threshold', 255) ?
                       'string_long' :
                       'string_short';
    }

    return $columnType;
  }
  
  public function getWidgetConfig($key = null)
  {
    if (!isset($this->widgetConfig))
    {
      $configFiles = array_filter($this->generatorManager->getConfiguration()->getConfigPaths('config/widget.yml'), 'is_readable');
      $this->widgetConfig = sfYamlConfigHandler::parseYamls($configFiles);
    }
    if (isset($key))
    {
      return $this->widgetConfig[$key];
    }
    return $this->widgetConfig;
  }

  public function getValidatorConfig($key = null)
  {
    if (!isset($this->validatorConfig))
    {

      $configFiles = array_filter($this->generatorManager->getConfiguration()->getConfigPaths('config/validator.yml'), 'is_readable');
      $this->validatorConfig = sfYamlConfigHandler::parseYamls($configFiles);
    }
    if (isset($key))
    {
      return $this->validatorConfig[$key];
    }
    return $this->validatorConfig;
  }

  /**
   * @param sfDoctrineColumn $column
   * @return string array exported form of messagses or "array()" if no messages exist
   */
  public function getMessagesForClass($class)
  {
    if (!isset($this->messages))
    {
      $configFiles = array_filter($this->generatorManager->getConfiguration()->getConfigPaths('config/message.yml'), 'is_readable');
      $this->messages = sfYamlConfigHandler::parseYamls($configFiles);
    }
    return isset($this->messages[$class]) ? $this->messages[$class] : array();
  }

  public function escape($string)
  {
    return sprintf('\'%s\'', $string);
  }

  /**
   * Differs from array export in that the values of this array are already escaped.
   * This is necessary because var_export will ALWAYS escape strings. Sometimes we want raw code.
   * @param array $array
   * @return string
   */
  public function preEscapedArrayExport($array)
  {
    $items = array();
    foreach($array as $key => $value)
    {
      $items[] = sprintf('\'%s\' => %s', $key, $value);
    }
    return sprintf('array(%s)', implode(', ', $items));
  }

  /**
   * Get array of templates that exist on the current model but not its parent.
   * If the template options for a template on the parent are different from the options of that template
   * on the child, that template will also be returned.
   * @param $table The table to get templates for
   * @param boolean $dependsOnOptions True to get template if the options on this class are different from the
   *        options on the parent class. False to leave out all templates that the parent has. (default: false)
   * @return array
   */
  public function getTemplates($table, $dependsOnOptions = false)
  {
    $parentModel = $this->generator->getParentModel();
    $parentTemplates = $parentModel ? Doctrine_Core::getTable($parentModel)->getTemplates() : array();
    $templates = $table->getTemplates();
    foreach($parentTemplates as $name => $parentTemplate)
    {
      if ($dependsOnOptions && !wfToolkit::arrayDeepEqual($parentTemplate->getOptions(), $templates[$name]->getOptions()))
      {
        unset($parentTemplates[$name]); # if we care about the options and they aren't the same, pretend the parent doesn't have the template (so it gets returned)
      }
    }
    return array_diff_key($templates, $parentTemplates);
  }

  /**
   * Get the template partial for a given table and suffix
   * @param Doctrine_Table $table The table for which to get the partial
   * @param string $type 'form' or 'filter'
   * @param string|null $suffix The suffix, if there is one
   * @param boolean $dependsOnOptions True to include this partial if the parent also has this template but the options on this class
   *        are different from the options on the parent class. False to ignore options differences. (default: false)
   * @return string|null The evaluated partial, or null  if no partial is found
   */
  public function getTemplatePartial($table, $type, $suffix = null, $dependsOnOptions = false)
  {
    $specialTemplateDefinitions = sfConfig::get('app_form_generator_template_type_map');
    $templateNames = array_keys($this->getTemplates($table, $dependsOnOptions));
    $toPrint = array();
    foreach($specialTemplateDefinitions as $templateName => $templateDefinition)
    {
      if (in_array('Doctrine_Template_'.$templateName, $templateNames))
      {
        $fileName = strtr('_%name%%type%%suffix%.php', array(
          '%name%' => lcfirst($templateName),
          '%type%' => ucfirst($type),
          '%suffix%' => $suffix ? '_'.$suffix : ''
        ));
        $output = $this->generator->evalTemplateIfExists($fileName);
        if ($output)
        {
          $toPrint[] = rtrim($output) . "\n";
        }
      }
    }

    return $toPrint ? implode("\n", $toPrint) : null;
  }
}