<?php
class wfWidgetFormTime extends sfWidgetForm
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * with_seconds:           Whether to include a select for seconds (false by default)
   *  * min_hour:               The minimum allowed hour (default: 0)
   *  * max_hour:               The maximum allowed hour (default: 23)
   *  * min_minute:             The minimum allowed minute (default: 0)
   *  * max_minute:             The maximum allowed minute (default: 59)
   *  * minute_interval:        The interval to create minutes (default: 1)
   *  * min_second:             The minimum allowed second (default: 0)
   *  * max_second:             The maximum allowed second (default: 59)
   *  * second_interval:        The interval to create seconds (default: 1)
   *  * can_be_empty:           Whether the widget accept an empty value (true by default)
   *  * empty_values:           An array of values to use for the empty value (empty string for hours, minutes, seconds, and meridiem by default)
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('with_seconds', false);
    $this->addOption('min_hour', 1);
    $this->addOption('max_hour', 24);
    $this->addOption('min_minute', 0);
    $this->addOption('max_minute', 59);
    $this->addOption('minute_interval', 1);
    $this->addOption('min_second', 0);
    $this->addOption('max_second', 59);
    $this->addOption('second_interval', 1);

    $this->addOption('can_be_empty', true);
    $this->addOption('empty_values', array('hour' => '', 'minute' => '', 'second' => '', 'meridiem' => ''));
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The time displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // convert value to an array
    $default = array('hour' => null, 'minute' => null, 'second' => null, 'meridiem' => null);
    if (is_array($value))
    {
      $value = array_merge($default, $value);
    }
    else
    {
      $value = ctype_digit($value) ? (integer) $value : strtotime($value);
      if (false === $value)
      {
        $value = $default;
      }
      else
      {
        // int cast required to get rid of leading zeros
        $value = array('hour' => (int) date('g', $value), 'minute' => (int) date('i', $value), 'second' => (int) date('s', $value), 'meridiem' => date('A', $value));
      }
    }

    $time = array();

    $widget = new sfWidgetFormSelect(array('choices' => $this->getChoices('hour'), 'id_format' => $this->getOption('id_format')), array_merge($this->attributes, $attributes));
    $time['%hour%'] = $widget->render($name.'[hour]', $value['hour']);

    // minutes
    $widget = new sfWidgetFormSelect(array('choices' => $this->getChoices('minute'), 'id_format' => $this->getOption('id_format')), array_merge($this->attributes, $attributes));
    $time['%minute%'] = $widget->render($name.'[minute]', $value['minute']);

    if ($this->getOption('with_seconds'))
    {
      // seconds
      $widget = new sfWidgetFormSelect(array('choices' => $this->getChoices('second'), 'id_format' => $this->getOption('id_format')), array_merge($this->attributes, $attributes));
      $time['%second%'] = $widget->render($name.'[second]', $value['second']);
    }

    $widget = new sfWidgetFormSelect(array('choices' => $this->getChoices('meridiem'), 'id_format' => $this->getOption('id_format')), array_merge($this->attributes, $attributes));
    $time['%meridiem%'] = $widget->render($name . '[meridiem]', $value['meridiem']);

    return strtr($this->getOption('with_seconds') ? '%hour%:%minute%:%second% %meridiem%' : '%hour%:%minute% %meridiem%', $time);
  }

  public function getChoices($type)
  {
    $emptyValues = $this->getOption('empty_values');
    $choices = array();
    if ($this->getOption('can_by_empty'))
    {
      $choices[''] = $emptyValues[$type];
    }

    switch($type)
    {
      case 'hour':
        $hours = array();
        if ($this->getOption('min_hour') <= 12)
        {
          $hours += static::myGenerateTwoCharsRange($this->getOption('min_hour'), min(12, $this->getOption('max_hour')));
        }
        if ($this->getOption('max_hour') >= 12)
        {
          $hours += static::myGenerateTwoCharsRange(max(1, $this->getOption('min_hour') - 12), $this->getOption('max_hour') - 12);
        }
        ksort($hours);
        $choices += $hours;
        return $choices;
      case 'minute':
      case 'second':
        $choices += static::myGenerateTwoCharsRange($this->getOption('min_' . $type), $this->getOption('max_' . $type), $this->getOption($type . '_interval'));
        return $choices;
      case 'meridiem':
        if ($this->getOption('min_hour') < 12)
        {
          $choices['AM'] = 'AM';
        }
        if ($this->getOption('max_hour') >= 12)
        {
          $choices['PM'] = 'PM';
        }
        return $choices;
    }
    
    throw new InvalidArgumentException(sprintf('Unknown type "%s"', $type));
  }

  /**
   * Generates a two chars range
   *
   * @param  int  $start
   * @param  int  $stop
   * @return array
   */
  static protected function myGenerateTwoCharsRange($start, $stop, $step = 1)
  {
    $results = range($start, $stop, $step);
    return array_combine($results, array_map(function($value) { return sprintf('%02d', $value); }, $results));
  }
}
