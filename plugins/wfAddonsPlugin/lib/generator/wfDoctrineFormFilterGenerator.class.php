<?php
class wfDoctrineFormFilterGenerator extends sfDoctrineFormFilterGenerator
{
  /**
   * Initializes the current sfGenerator instance.
   *
   * @param sfGeneratorManager A sfGeneratorManager instance
   * @see sfDoctrineFormGenerator::initialize
   */
  public function initialize(sfGeneratorManager $generatorManager)
  {
    parent::initialize($generatorManager);
    $this->tools = new wfDoctrineFormGeneratorTools($generatorManager, $this); //used to share code between form and filter generator
    $this->setTheme(sfConfig::get('app_form_generator_filter_theme'));
  }

  /**
   * Returns a sfWidgetForm class name for a given column.
   *
   * @param  sfDoctrineColumn $column
   * @return string    The name of a subclass of sfWidgetForm
   */
  public function getWidgetClassForColumn($column)
  {
    $widgetClasses = $this->tools->getWidgetConfig('filter_classes');
    $formColumnType = $this->tools->getFormColumnType($column);
    return isset($widgetClasses[$formColumnType]) ?
           $widgetClasses[$formColumnType] :
           parent::getWidgetClassForColumn($column);
  }


  /**
   * Returns a sfValidator class name for a given column.
   *
   * @param sfDoctrineColumn $column
   * @return string    The name of a subclass of sfValidator
   */
  public function getValidatorClassForColumn($column)
  {
    $validatorClasses = array_merge($this->tools->getValidatorConfig('classes'), $this->tools->getValidatorConfig('filter_classes'));
    $formColumnType = $this->tools->getFormColumnType($column);

    return isset($validatorClasses[$formColumnType]) ? 
           $validatorClasses[$formColumnType] :
           $validatorClasses['default'];
  }

  /**
   * Just like sfGenerator->evalTemplate(), but doesn't thrown an exception if the template doesn't exist.
   * THIS CAN'T GO INTO TOOLS BECAUSE evalTemplate() IS PROTECTED
   * @param string $templateFile The name of the template file
   * @return string|null The evaluated template, or null if no template was found
   */
  public function evalTemplateIfExists($templateFile)
  {
    $dirs = $this->generatorManager->getConfiguration()->getGeneratorTemplateDirs($this->getGeneratorClass(), $this->getTheme());
    $fullTemplateFile = null;
    foreach ($dirs as $dir)
    {
      if (is_readable($dir.'/'.$templateFile))
      {
        return $this->evalTemplate($templateFile);
      }
    }

    return null;
  }

  /**
   * Get additional partials for special templates
   * @param string|null $suffix A suffix for the partial filename. Used to insert multiple partials for the same template
   * @param boolean $dependsOnOptions True to include this partial if the parent also has this template but the options on this class
   *        are different from the options on the parent class. False to ignore options differences. (default: false)
   * @param Doctrine_Table $table The table that the template is for.
   */
  public function insertTemplatePartials($suffix = null, $dependsOnOptions = false, $table = null)
  {
    $table = $table ?: $this->table ?: null;
    if (!$table)
    {
      throw new InvalidArgumentException('$table must be specified if this function is called outside of a template.');
    }

    echo $this->tools->getTemplatePartial($table, 'filter', $suffix, $dependsOnOptions);
  }
}