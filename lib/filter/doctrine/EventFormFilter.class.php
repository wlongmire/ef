<?php

/**
 * Event filter form.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventFormFilter extends BaseEventFormFilter
{
  public function configure()
  {    
    $this->setWidget('event_type_id', new myWidgetFormFilterTree(array(
      'tree' => EventTypeTable::getInstance()->getTree()->findSortedTrees(),
    )));  
    
    $choices = array('no' => 'No', 'yes' => 'Yes');
    $this->setWidget('include_past_events', new sfWidgetFormSelect(array(
        'choices' => $choices
    )));
    $this->setValidator('include_past_events', new sfValidatorChoice(array(
        'choices' => array_keys($choices)
    )));
    
    $this->setWidget('venue_id', new wfWidgetFormJqueryDoctrineAutocompleter(array(
      'model' => 'Venue',
      'value_callback' => array($this, 'getVisibleValueForVenueId')
    ), array(
      'class' => 'lock'
    )));      
    
    $this->useFields(array('name', 'event_type_id', 'venue_id', 'include_past_events'));
  }
  
  public function addIncludePastEventsColumnQuery($query, $field, $value)
  {
    if ($value == 'no')
    {
      $query->leftJoin($query->getRootAlias() . '.EventOccurances eo');
      $query->addWhere('eo.end_date >= ?', date('Y-m-d'));
    }
  }
  
  public function getVisibleValueForVenueId($id)
  {
    if (!is_numeric($id))
    {
      return $id;
    }
    $venue = Doctrine_Core::getTable('Venue')->findOneBy('id', $id);
    if ($venue)
    {
      return $venue->name;
    }
    return '';
  }
}
