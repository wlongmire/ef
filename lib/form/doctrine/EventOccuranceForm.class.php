<?php

/**
 * EventOccurance form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventOccuranceForm extends BaseEventOccuranceForm
{
  public function configure()
  {    
    unset($this['id'], $this['event_id']);
    
    $this->setWidget('start_date', new wfWidgetFormJQueryDate());
    $this->setWidget('end_date', new wfWidgetFormJQueryDate());
    
    $this->setWidget('start_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 60, 'type' => 'text')));    
    $this->setWidget('end_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 60, 'type' => 'text')));
    $this->setDefault('start_time', '06:00 PM'); 
  }
  
  public function doUpdateObject($values)
  {
    if (!$values['end_date'])
    {
      $values['end_date'] = $values['start_date'];
    }
    parent::doUpdateObject($values);
  }
}
