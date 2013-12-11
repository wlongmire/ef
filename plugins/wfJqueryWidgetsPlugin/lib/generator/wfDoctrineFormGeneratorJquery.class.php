<?php

/**
 * Extention of wfDoctrineFormGenerator to add Jquery specific widget options and attributes
 *
 * @author Jeremy Kauffman <kauffj@gmail.com>
 */
class wfDoctrineFormGeneratorJquery extends wfDoctrineFormGenerator
{  
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
    $options = parent::getWidgetOptionsForColumnUnformatted($column, $formColumnType);
    $specialTypes = $this->tools->getWidgetConfig('special_types');

    if (!isset($specialTypes[$formColumnType]))
    {
      switch($formColumnType)
      {
        case 'date':
          if (isset($column['range']))
          {
            if (isset($column['range'][0]))
            {
              $options['min_date'] = $column['range'][0];
            }
            if (isset($column['range'][1]))
            {
              $options['max_date'] = $column['range'][0];
            }
          }
      }
    }
    return $options;
  }

    /**
   * @param sfDoctrineColumn $column
   * @param string $formColumnType a wfDoctrineFormGeneratorTools::getFormColumnType value
   * @return array An array of attributes
   */
  public function getWidgetAttributesForColumnUnformatted(sfDoctrineColumn $column, $formColumnType)
  {
    $attributes = parent::getWidgetAttributesForColumnUnformatted($column, $formColumnType);
    $specialTypes = $this->tools->getWidgetConfig('special_types');
    $classes = array();
    
    if (!isset($specialTypes[$formColumnType]))
    {
      switch ($formColumnType)
      {
        case 'integer':
        case 'decimal':
        case 'float':
          $classes[] = 'number';
          if (isset($column['range']))
          {
            if (isset($column['range'][0]))
            {
              $attributes['min'] = $column['range'][0];
            }
            if (isset($column['range'][1]))
            {
              $attributes['max'] = $column['range'][1];
            }
          }
          if ($column->isNotNull())
          {
            $classes[] = 'required';
          }
          break;
        case 'email':
          $classes[] = 'email';
        case 'clob':
        case 'blob':
        case 'string_long':
        case 'string_short':
          if (isset($column['minlength']))
          {
            $attributes['minlength'] = $column['minlength'];
          }
          if (isset($column['regexp']))
          {
            $attributes['pattern'] = $this->tools->escape($column['regexp']);
          }
          if ($column->isNotNull())
          {
            $classes[] = 'required';
          }
        break;
        case 'date':
          $classes[] = 'date';
        case 'timestamp':
          if ($column->isNotNull())
          {
            $classes[] = 'required';
          }
          break;
        case 'time':
//          if (isset($column['timerange']))
//          {
//            if (isset($column['timerange'][0]))
//            {
//              $attributes[] = sprintf('\'min\' => \'%s\'', $column['timerange'][0]);
//            }
//            if (isset($column['timerange'][1]))
//            {
//              $attributes[] = sprintf('\'max\' => \'%s\'', $column['timerange'][1]);
//            }
//          }
//          $attributes[] = sprintf('\'required\' => %s', $column->isNotNull() ? 'true' : 'false');
          break;
      }
    }

    if ($classes)
    {
      $attributes['class'] = $this->tools->escape(implode(' ', $classes));
    }

    return $attributes;
  }
}