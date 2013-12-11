<?php

/**
 * A form generator that allows for greater configuration of classes and options.
 *
 * @author Jeremy Kauffman <kauffj@gmail.com>
 */
class wfDoctrineFormGenerator extends sfDoctrineFormGenerator
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
    $this->setTheme(sfConfig::get('app_form_generator_form_theme'));
  }

  /**
   * Returns a sfWidgetForm class name for a given column.
   *
   * @param  sfDoctrineColumn $column
   * @return string    The name of a subclass of sfWidgetForm
   */
  public function getWidgetClassForColumn($column)
  {
    $widgetClasses = $this->tools->getWidgetConfig('classes');
    $formColumnType = $this->tools->getFormColumnType($column);
    return isset($widgetClasses[$formColumnType]) ?
             $widgetClasses[$formColumnType] :
             $widgetClasses['default'];
  }

  /**
   * Returns a PHP string representing options to pass to a widget for a given column.
   *
   * @param  sfDoctrineColumn $column
   * @return string    The options to pass to the widget as a PHP string
   */
  public function getWidgetOptionsForColumn($column)
  {
    $formColumnType = $this->tools->getFormColumnType($column);
    $options = $this->getWidgetOptionsForColumnUnformatted($column, $formColumnType); //call through to here for extensibility purposes
    return $this->tools->preEscapedArrayExport($options);
  }

  /**
   * Returns an array of options to use to create the widget for the column.
   * 
   * This function is separated out from getWidgetOptionsForColumn so it can be extended
   *
   * @param sfDoctrineColumn $column
   * @param  string $formColumnType a type returned by wfDoctrineFormGeneratorTools::getFormColumnType
   * @return array An array of the options to pass to the widget as a PHP string
   */
  public function getWidgetOptionsForColumnUnformatted(sfDoctrineColumn $column, $formColumnType)
  {
    $options = array();
    $specialTypes = $this->tools->getWidgetConfig('special_types');

    if (isset($specialTypes[$formColumnType]))
    {
      if ($formColumnType == 'foreign_key')
      {
        $options['model'] = sprintf('$this->getRelatedModelName(\'%s\')', $column->getRelationKey('alias'));
        $options['add_empty'] = $column->isNotNull() ? 'false' : 'true';
      }
      if ($formColumnType == 'image')
      {
        $options['required'] = $column->isNotNull() ? 'true' : 'false';
        $options['record'] = '$this->getObject()';
      }
      if ($formColumnType == 'usstate')
      {
        $options['with_empty'] = $column->isNotNull() ? 'false' : 'true';
      }
    }
    else
    {
      switch ($formColumnType)
      {
        case 'enum':
          $values = $column->getDefinitionKey('values');
          $values = array_combine($values, $values);
          $expanded = count($values) <= sfConfig::get('app_form_generator_enum_expanded_threshold');
          $options['choices'] =  str_replace("\n", '', $this->arrayExport($values));
          $options['expanded'] = $expanded ? 'true' : 'false';
          break;
      }
    }
    return $options;
  }

  /**
   * Returns a PHP string representing attributes to pass to a widget for a given column.
   *
   * @param  sfDoctrineColumn $column
   * @return string    The attributes to pass to the widget as a PHP string
   */
  public function getWidgetAttributesForColumn($column)
  {
    $formColumnType = $this->tools->getFormColumnType($column);
    $attributes = $this->getWidgetAttributesForColumnUnformatted($column, $formColumnType); //call through to here for extensibility purposes
    return $this->tools->preEscapedArrayExport($attributes);
  }

  /**
   * @param sfDoctrineColumn $column
   * @param string $formColumnType a wfDoctrineFormGeneratorTools::getFormColumnType value
   * @return array An array of attributes as key value pairs
   */
  public function getWidgetAttributesForColumnUnformatted(sfDoctrineColumn $column, $formColumnType)
  {
    $autosizeSettings = $this->tools->getWidgetConfig('autosize_settings');
    $formColumnType = $this->tools->getFormColumnType($column);

    if (isset($autosizeSettings[$formColumnType]))
    {
      $sizeSettings = $autosizeSettings[$formColumnType];
      if (isset($sizeSettings['rows']) && isset($sizeSettings['cols']))
      {
        $attributes = array();
        if (isset($sizeSettings['rows']))
        {
          $attributes['rows'] = $sizeSettings['rows'];
        }
        if (isset($sizeSettings['cols']))
        {
          $attributes['cols'] = $sizeSettings['cols'];
        }
        return $attributes;
      }
      else
      {
        return $this->getSizeableWidgetAttribute($column, $sizeSettings);
      }
    }

    return array();
  }

  /**
   * @param sfDoctrineColumn $column
   * @param array $sizeSettings
   * @return array An array potentially containing maxlength and size
   */
  public function getSizeableWidgetAttribute(sfDoctrineColumn $column, array $sizeSettings)
  {
    $attributes = array();
    $force = isset($sizeSettings['force']) && $sizeSettings['force'];
    if ($force)
    {
      if (isset($sizeSettings['length']))
      {
        $attributes['maxlength'] = $sizeSettings['length'];
      }
      if (isset($sizeSettings['size']))
      {
        $attributes['size'] = $sizeSettings['size'];
      }
    }
    if (!isset($sizeSettings['length']))
    {
      if (isset($column['range']) && isset($column['range'][1]))
      {
        $length = max(array(strlen($column['range'][1]), $column['length']));
      }
      elseif (isset($column['length']))
      {
        if ($column->getType() == 'INTEGER')
        {
          switch($column['length'])
          {
            case 1:
              $length = 2; break;
            case 2:
              $length = 6; break;
            case 3:
              $length = 9; break;
            case 4:
              $length = 12; break;
            default:
              $length = 20;
          }
        }
        else
        {
          $length = $column['length'];
        }
      }
      elseif (isset($sizeSettings['default_length']))
      {
        $length = $sizeSettings['default_length'];
      }
      if (isset($length))
      {
        $attributes['maxlength'] = $length;
      }
    }
    if (!isset($attributes['size']))
    {
      if (isset($length))
      {
        $size = $length;
      }
      elseif (isset($sizeSettings['default_size']))
      {
        $size = $sizeSettings['default_size'];
      }
      if (isset($size))
      {
        if(isset($sizeSettings['max_size']))
        {
          $size = min($size, $sizeSettings['max_size']);
        }
        if (isset($sizeSettings['min_size']))
        {
          $size = max($size, $sizeSettings['min_size']);
        }
        $attributes['size'] = isset($length) ? min($size, $length) : $size;
      }
    }
    return $attributes;
  }

  /**
   * @return string The name of the widget class for many-to-many relations
   */
  public function getManyToManyWidgetClass()
  {
    $widgetClasses = $this->tools->getWidgetConfig('classes');
    return $widgetClasses['many_to_many'];
  }
  
  /**
   * Returns a sfValidator class name for a given column.
   *
   * @param sfDoctrineColumn $column
   * @return string    The name of a subclass of sfValidator
   */
  public function getValidatorClassForColumn($column)
  {
    $validatorClasses = $this->tools->getValidatorConfig('classes');
    $formColumnType = $this->tools->getFormColumnType($column);
        
    return isset($validatorClasses[$formColumnType]) ?
             $validatorClasses[$formColumnType] :
             $validatorClasses['default'];
  }

  /**
   * Returns a PHP string representing options to pass to a validator for a given column.
   *
   * @param sfDoctrineColumn $column
   * @return string    The options to pass to the validator as a PHP string
   */
  public function getValidatorOptionsForColumn($column)
  {
    $formColumnType = $this->tools->getFormColumnType($column);
    $options = $this->getValidatorOptionsForColumnUnformatted($column, $formColumnType);
    return $this->tools->preEscapedArrayExport($options);
  }

  /**
   * This function exists for extensibility.
   * @param sfDoctrineColumn $column
   * @return array An array of options as key value pairs. Values are already escaped if necessary.
   */
  public function getValidatorOptionsForColumnUnformatted(sfDoctrineColumn $column, $formColumnType)
  {
    $options = array();
    $specialTypes = $this->tools->getWidgetConfig('special_types');

    if (isset($specialTypes[$formColumnType]))
    {
      switch ($formColumnType)
      {
        case 'foreign_key':
          $options['model'] = $this->tools->escape($column->getForeignTable()->getOption('name'));
          break;
        case 'primary_key':
          $options['model'] = $this->tools->escape($this->modelName);
          $options['column'] = $this->tools->escape($column->getName());
          break;
        case 'image':
          $options['model'] = $this->tools->escape($this->modelName);
          break;
      }
    }
    else
    {
      switch ($formColumnType)
      {
        case 'string_short':
        case 'string_long':
          if ($column['length'])
          {
            $options['max_length'] = $column['length'];
          }
          if (isset($column['minlength']))
          {
            $options['min_length'] = $column['minlength'];
          }
          if (isset($column['regexp']))
          {
            $options['pattern'] = $this->tools->escape($column['regexp']);
          }
          if (isset($column['nohtml']))
          {
            $options['nohtml'] = $column['nohtml'] ? 'true' : 'false';
          }
          if (isset($column['email']))
          {
            $checkMx = is_array($column['email']) && $column['email']['check_mx'];
            $options['check_mx'] = $checkMx ? 'true' : 'false';
          }
          if (isset($column['exclude']))
          {
          	$options['exclude'] = $this->arrayExport($column['exclude']);
          }
          break;
        case 'enum':
          $values = array_combine($column['values'], $column['values']);
          $options['choices'] = str_replace("\n", '', $this->arrayExport($values));
          break;
        case 'decimal':
        case 'float':
        case 'integer':
          if (isset($column['range']))
          {
            if (isset($column['range'][0]))
            {
              $options['min'] = $column['range'][0];
            }
            if (isset($column['range'][1]))
            {
              $options['max'] = $column['range'][1];
            }
          }
          break;
        case 'date':
        case 'timestamp':
          if (isset($column['daterange']))
          {
            if (isset($column['daterange'][0]))
            {
              $options['min_dt'] = $this->tools->escape($column['daterange'][0]);
            }
            if (isset($column['daterange'][1]))
            {
              $options['max_dt'] = $this->tools->escape($column['daterange'][1]);
            }
          }
          break;
        case 'time':
          if (isset($column['timerange']))
          {
            if (isset($column['timerange'][0]))
            {
              $options['min'] = $this->tools->escape($column['timerange'][0]);
            }
            if (isset($column['timerange'][1]))
            {
              $options['max'] = $this->tools->escape($column['timerange'][1]);
            }
          }
          break;
      }
    }

    if (!$column->isNotNull() || $column->isPrimaryKey())
    {
      $options['required'] = 'false';
    }

    return $options;
  }

  /**
   * @param string $validatorClass
   * @return string array exported form of messagses or "array()" if no messages exist
   */
  public function getValidatorMessages($validatorClass)
  {
    return $this->arrayExport($this->tools->getMessagesForClass($validatorClass));
  }

  public function getUniqueValidatorClass()
  {
    $validatorClasses = $this->tools->getValidatorConfig('classes');
    return $validatorClasses['unique'];
  }

  public function getUniqueValidatorOptions($columns)
  {
    return $this->tools->preEscapedArrayExport($this->getUniqueValidatorOptionsUnformatted($columns));
  }

  public function getUniqueValidatorOptionsUnformatted($columns)
  {
    return array(
        'model' =>  $this->tools->escape($this->table->getOption('name')),
        'column' => $this->arrayExport($columns),
        'object' => '$this->object'
    );
  }

  /**
   * @return string The name of the validator class for many-to-many relations
   */
  public function getManyToManyValidatorClass()
  {
    $validatorClasses = $this->tools->getValidatorConfig('classes');
    return $validatorClasses['many_to_many'];
  }
  
  /**
   * @param sfDoctrineColumn $column
   * @return boolean True if the validator type for $column is a subclass of sfValidatorFile, false otherwise
   */
  public function requiresFileValidator(sfDoctrineColumn $column)
  {
    $validatorClass = $this->getValidatorClassForColumn($column);
    if (class_exists($validatorClass))
    {
      $reflection = new ReflectionClass($validatorClass);
      return $reflection->isSubclassOf('sfValidatorFile');
    }
    return false;
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
   * @param string $suffix A suffix for the partial filename. Used to insert multiple partials for the same template
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

    echo $this->tools->getTemplatePartial($table, 'form', $suffix, $dependsOnOptions);
  }
}