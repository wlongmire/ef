<?php

/**
 * wfWidgetFormJQueryDate represents a JQuery date widget.
 *
 * This widget needs JQuery and JQuery UI to work.
 */
class wfWidgetFormJqueryDate extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * config:      A JavaScript array that configures the JQuery date widget
   *  * culture:     The user culture
   *  * date_widget: The date widget instance to use as a "base" class
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('min_date');
    $this->addOption('max_date');
    $this->addOption('date_format', 'm/d/Y');
    $this->addOption('date_widget', new sfWidgetFormInputText());

    $this->setAttribute('size', 10);
    $this->setAttribute('length', 10);

    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value)
    {
      if (is_numeric($value))
      {
        $value = '@' . $value;
      }
      try
      {
        $dt = new DateTime($value);
        $value = $dt->format($this->getOption('date_format'));
      }
      catch (Exception $e) { }
    }

    $attributes['class'] = 'date' . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

    if ($this->getOption('min_date'))
    {
      $dt = $this->getOption('min') instanceof DateTime ? $this->getOption('min') : new DateTime($this->getOption('min'));
      $attributes['mindate'] = $dt->format('m/d/Y');
    }
    if ($this->getOption('max_date'))
    {
      $dt = $this->getOption('max') instanceof DateTime ? $this->getOption('max') : new DateTime($this->getOption('max'));
      $attributes['maxdate'] = $dt->format('m/d/Y');
    }
    
    return $this->getOption('date_widget')->render($name, $value, $attributes, $errors);
  }

  public function getStylesheets()
  {
    return array();
  }

  public function getJavascripts()
  {
    return array('/wfJqueryWidgets/js/date');
  }
}
