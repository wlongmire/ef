<?php

/**
 * EventRecurrance form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventRecurranceForm extends BaseEventRecurranceForm
{
  public function configure()
  {
    unset($this['id'], $this['event_id']);
    $this->days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
    foreach($this->days as $day)
    {
      $this->setDefault($day, false);
    }
    
    $this->setWidget('start_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 15, 'type' => 'text')));    
    $this->setWidget('end_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 15, 'type' => 'text')));
    
    $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'ensureDaySelectedCallback')
    )));
  }
  
  public function ensureDaySelectedCallback(sfValidatorBase $validator, $values)
  {
    foreach($this->days as $day)
    {
      if (isset($values[$day]) && $values[$day])
      {
        return $values;
      }
    }
    
    throw new sfValidatorErrorSchema($validator, array(
        'sunday' => new sfValidatorError($validator, 'You must select at least one day for a recurring event')
    ));
  }
}
