<?php

/*
 * Extend to add inline help
 */
class myWidgetFormSelectCheckbox extends sfWidgetFormSelectCheckbox
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('helps', array());
  }

  protected function formatChoices($name, $value, $choices, $attributes)
  {
    $helps = $this->getOption('helps');

    $inputs = array();
    foreach ($choices as $key => $option)
    {
      $baseAttributes = array(
        'name'  => $name,
        'type'  => 'checkbox',
        'value' => self::escapeOnce($key),
        'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
      );

      if ((is_array($value) && in_array(strval($key), $value)) || (!is_array($value) && strval($key) == strval($value)))
      {
        $baseAttributes['checked'] = 'checked';
      }

      $inputs[$id] = array(
        'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
        'label' => $this->renderContentTag('label', self::escapeOnce($option), array('for' => $id)),
        'help' => isset($helps[$key]) ? $helps[$key] : false
      );
    }

    return call_user_func($this->getOption('formatter'), $this, $inputs);
  }

  public function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $input)
    {
      $row = $input['input'].$this->getOption('label_separator').$input['label'];
      if ($input['help'])
      {
        $row .= '<span class="help standard">' . $input['help'] . '</span>';
      }
      $rows[] = '<li>' . $row . '</li>';
    }

    return !$rows ? '' : $this->renderContentTag('ul', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class')));
  }
}
