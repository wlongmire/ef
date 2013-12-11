<?php
class wfWidgetFormJqueryTextarea extends sfWidgetFormTextarea
{
  public function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('wrapper', true);
    $this->addOption('autosize', true);
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($this->getOption('autosize'))
    {
      $attributes['class'] = 'autosize' . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');
    }
    $textarea = parent::render($name, $value, $attributes, $errors);
    if ($this->getOption('wrapper'))
    {
      return sprintf('<div class="textareaWrapper">%s</div>', $textarea);
    }
    return $textarea;
  }

  public function getJavascripts()
  {
    return $this->getOption('autosize') ? array('/wfJqueryWidgets/js/vendor/autoresize.jquery.js') : array();
  }
}