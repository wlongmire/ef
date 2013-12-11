<?php

/**
 * Description of myWidgetFormEventType
 *
 * @author jeremy
 */
class myWidgetFormEventType extends sfWidgetFormChoiceBase
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * choices:  An array of possible choices (required)
   *  * multiple: true if the select tag must allow multiple selections
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoiceBase
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('multiple', false);
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return $this->renderContentTag('select', "\n".implode("\n", $this->getOptionsForSelect($value))."\n", array_merge(array('name' => $name), $attributes));
  }

  /**
   * Returns an array of option tags for the given choices
   *
   * @param  string $value    The selected value
   *
   * @return array  An array of option tags
   */
  protected function getOptionsForSelect($value)
  {
    $mainAttributes = $this->attributes;
    $this->attributes = array();

    if (!is_array($value))
    {
      $value = array($value);
    }

    $value = array_map('strval', array_values($value));
    $value_set = array_flip($value);

    $options = array($this->renderContentTag('option', '', array('value' => '')));
    foreach ($this->getOption('choices') as $eventType)
    {
      $attributes = array('value' => $eventType['id'], 'data-is-daily' => $eventType['is_daily'] ? 1 : 0);
      if (isset($value_set[$eventType['id']]))
      {
        $attributes['selected'] = 'selected';
      }

      $name = str_repeat('--', $eventType['level']) . $eventType['name'];

      $options[] = $this->renderContentTag('option', self::escapeOnce($name), $attributes);
    }

    $this->attributes = $mainAttributes;

    return $options;
  }
}