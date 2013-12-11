<?php

/**
 * wfWidgetFormJQuerySlider represents a JQuery slider widget.
 *
 * This widget needs JQuery and JQuery UI to work.
 */
class wfWidgetFormJquerySlider extends sfWidgetFormInput
{
  /**
   * Configures the current widget.
   *
   * Available options for the sliderOptions array:
   *
   *  * min:           The smallest slider value
   *  * max:           The largest slider value
   *  * step:          The incrememt between values
   *  * default_value: The default starting value for the slider
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('min', 1);
    $this->addOption('max', 100);
    $this->addOption('step', 1);
    $this->addOption('defaultValue', 50);
    $this->addOption('valueLabelMap', array()); // if set, map values to text and show text
    $this->addOption('appendValueToLabel', true); // if set, append value to label text

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
    sfContext::getInstance()->getConfiguration()->loadHelpers('Tag');

    $id = $this->generateId($name);
    $render['hidden'] = $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value, 'id' => $id));

    $widgetAttributes = $attributes;
    $widgetAttributes['class'] = (isset($attributes['class']) ? $attributes['class'] . ' ' : '') . 'ui-slider-widget';


    $startValue = $value !== null ? $value : $this->getOption('defaultValue');
    $labelStartValue =  $this->getLabelStartValue($startValue);

    $sliderAttributes = array(
      'class' => 'slider',
      'data-for' => $id,
      'data-options' => json_encode(array(
        'min' => $this->getOption('min'),
        'max' => $this->getOption('max'),
        'step' => $this->getOption('step'),
        'value' => $startValue,
        'valueLabelMap' => $this->getOption('valueLabelMap'),
        'appendValueToLabel' => $this->getOption('appendValueToLabel')
      ))
    );

    $valueAttributes['class'] = 'ui-slider-value';

    $render['widget'] =
      content_tag('div',
                  content_tag('div', '', $sliderAttributes) . content_tag('span', $labelStartValue, $valueAttributes),
                  $widgetAttributes);

    return implode("\n", $render);
  }

  /**
   * Returns the default value of the label. THIS MUST MATCH THE getValueText() FUNCTION IN slider.js
   *
   * @param array|null $valueLabelMap
   * @param integer $startValue
   */
  protected function getLabelStartValue($startValue)
  {
    $valueLabelMap = $this->getOption('valueLabelMap');
    if (!$valueLabelMap)
    {
      return $startValue;
    }

    foreach ($valueLabelMap as $key => $value)
    {
      if ($key > $startValue)
      {
        break;
      }
      $text = $value . ($this->getOption('appendValueToLabel') ? ' (' . $startValue . ')' : '');
    }
    
    return $text;
  }

  public function getStylesheets()
  {
    return array('/wfJqueryWidgets/css/slider' => 'all');
  }

  public function getJavascripts()
  {
    return array('/wfJqueryWidgets/js/slider');
  }
}