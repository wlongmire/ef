<?php
class myWidgetFormJQueryTime extends aWidgetFormJQueryTime
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->setAttribute('twenty-four-hour', false);
    $this->setAttribute('minutes-increment', 5);
    parent::configure($options, $attributes);
  }
}