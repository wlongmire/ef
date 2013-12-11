<?php

/**
 * wfWidgetFormJqueryDoctrineAutocompleter represents an autocomplete input widget rendered by JQuery for Doctrine objects.
 *
 * This widget needs JQuery to work.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormJQueryAutocompleter.class.php 31062 2010-10-06 13:54:38Z fabien $
 */
class wfWidgetFormJqueryDoctrineAutocompleter extends sfWidgetFormInputText
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * model:          The model class the autocomplete widget is for
   *  * config:         A JavaScript array that configures the JQuery autocomplete widget
   *  * value_callback: A callback that converts the value before it is displayed
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('value_callback');
    $this->addOption('query_options', array());
    $this->addOption('config', array());

    // this is required as it can be used as a renderer class for sfWidgetFormChoice
    $this->addOption('choices');

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
    $render = array();

    $id = $this->generateId($name);
    $visibleValue = $this->getOption('value_callback') ? call_user_func($this->getOption('value_callback'), $value) : $value;
    $render['hidden'] = $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value, 'id' => $id));

    $urlParameters = array_merge($this->getOption('query_options'), array('model' => $this->getOption('model')));

    $widgetAttributes = $attributes;
    $widgetAttributes['class'] = (isset($attributes['class']) ? $attributes['class'] . ' ' : '') . 'autocomplete';
    $widgetAttributes['data-source'] = sfContext::getInstance()->getRouting()->generate('wf_live_search', $urlParameters);
    $widgetAttributes['data-for'] = $id;

    $render['widget'] = parent::render('autocomplete_'.$name, $visibleValue, $widgetAttributes, $errors);

    return implode("\n", $render);
  }
  
  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
//  public function getJavascripts()
//  {
//    return array('/wfJqueryWidgets/js/vendor/jquery.tmpl.js', '/wfJqueryWidgets/js/autocomplete');
//  }
}
