<?php

/**
 * wfWidgetFormJQueryDoctrineSelectMany represents a JQuery based multi select widget.
 *
 * This widget needs JQuery to work.
 */
class wfWidgetFormJqueryDoctrineSelectMany extends wfWidgetFormDoctrineChoice
{
  /**
   * Configures the current widget. Makes "query" option required, "table_method" is not supported.
   *
   * Available options:
   *
   * * current_header: The text to display above currently selected*
   * * autocomplete_class: The class to use for the autocomplete
   * * autocomplete_options: The options to pass to the autocomplete.
   * * autocomplete_header: The text to display above the search
   * * autocomplete_placeholder: The text to display as a placeholder in the search box
   * * draggable: can items be dragged between select many instances
   * * droppable_help: the message to be displayed when the "over" event fires on droppable
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addRequiredOption('query');
    $this->addOption('current_header', 'Current');
    $this->addOption('style', 'rich');
    $this->addOption('autocomplete_class', 'wfWidgetFormJqueryDoctrineAutocompleter');
    $this->addOption('autocomplete_options', array());
    $this->addOption('autocomplete_header', 'Add');
    $this->addOption('autocomplete_placeholder', 'Search');
    $this->addOption('draggable', false);
    $this->addOption('droppable_help', '');
    $this->addOption('list_item_callback', null);
    $this->setOption('add_empty', false);
    $this->setOption('multiple', true);

    if (isset($options['table_method']))
    {
      throw new InvalidArgumentException('Option "table_method" not supported.');
    }
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
    $query = $this->getOption('query');
    if ($value)
    {
      $query->andWhereIn(sprintf('%s.id', $query->getRootAlias()), $value);
    }
    else
    {
      $query->andWhere(sprintf('%s.id IS NULL', $query->getRootAlias()));
    }
    $this->setOption('query', $query);
    
    $render = array();
    $render['select'] = sprintf('<div class="ui-helper-hidden">%s</div>', parent::render($name, $value, $attributes, $errors));

    $render['autocomplete'] = $this->renderAutocompleter($name, $value, $attributes, $errors);
    $render['list'] = $this->renderList($name, $value, $attributes, $errors);
    if ($this->getOption('droppable_help'))
    {
      $render['droppable_help'] = '<div class="droppable-help">' . $this->getOption('droppable_help') . '</div>';
    }
    return sprintf('<noscript><span class="notice">Requires javascript.</span></noscript>
                     <div class="wf-select-many %s %s ui-widget ui-helper-clear-fix" style="display: none">%s</div>',
                        $this->getOption('style'), $this->getOption('draggable') ? 'draggable' : '', implode("\n", $render));
  }

  public function renderAutocompleter($name, $value = null, $attribute = array(), $errors = array())
  {
    $autocomplete = $this->getAutocomplete();
    $parts = array();
    if ($this->getOption('autocomplete_header'))
    {
      $parts[] = sprintf('<div class="ui-widget-header ui-corner-top">%s</div>', $this->getOption('autocomplete_header'));
    }
    $parts[] = sprintf('<div class="ui-widget-content">%s</div>', $autocomplete->render('autocomplete_' . $name));
    return sprintf('<div class="autocomplete-wrapper">%s</div>', implode("\n", $parts));
  }

  public function renderList($name, $value = null, $attribute = array(), $errors = array())
  {
    $list = array();
    $callback = $this->getOption('list_item_callback');
    if ($this->getChoiceObjects())
    {
      if ($callback)
      {
        foreach($this->getChoiceObjects() as $choice) //cache, so no cost to do this twice
        {
          $list[] = call_user_func($callback, $choice);
        }
      }
      else
      {
        $template = '<div class="item" data-key="%key%"><span class="ui-icon-wrapper"><span class="ui-icon ui-icon-trash remove"></span></span>%choice%</div>';
        foreach($this->getChoices() as $key => $choice)
        {
          $list[] = strtr($template, array('%key%' => $key, '%choice%' => $choice)); //make sure template is changed if this changes
        }
      }
    }
    $list[] = '<div class="empty">None selected.</div>'; //always add empty, it'll get hidden

    $parts = array();
    if ($this->getOption('current_header'))
    {
      $parts[] = sprintf('<div class="ui-widget-header ui-corner-top">%s</div>', $this->getOption('current_header'));
    }
    $parts[] = sprintf('<div class="ui-widget-content current-list">%s</div>', implode("\n", $list));
    return sprintf('<div class="current">%s</div>', implode("\n", $parts));
  }

  public function getAutocomplete($useCached = true)
  {
    if (!isset($this->autocomplete) || !$useCached)
    {
      $class = $this->getOption('autocomplete_class');
      $options = array_merge(array('model' => $this->getOption('model')), $this->getOption('autocomplete_options'));
      $attributes = array();
      if ($this->getOption('autocomplete_placeholder'))
      {
        $attributes['placeholder'] = $this->getOption('autocomplete_placeholder');
      }
      $this->autocomplete = new $class($options, $attributes);
    }
    return $this->autocomplete;
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/wfJqueryWidgets/css/select_many' => 'all');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
//  public function getJavascripts()
//  {
//    return array_merge($this->getAutocomplete()->getJavascripts(),
//              array('/wfJqueryWidgets/js/vendor/jquery.tmpl.js', '/wfJqueryWidgets/js/select_many'));
//  }
}
