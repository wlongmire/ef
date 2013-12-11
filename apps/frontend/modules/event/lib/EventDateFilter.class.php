<?php
class EventDateFilter extends BaseForm
{
  public function configure()
  {
    $this->setWidget('start_date', new wfWidgetFormJqueryDate(array(
        'label' => 'Starting'
    )));
    $this->setValidator('start_date', new sfValidatorDate());
    
    $dateRange =  array(1,2,3,7,14,21,28,90);
    $dateLabels = array_map(function($date) { return $date . ' day' . ($date > 1 ? 's' : ''); }, $dateRange);
    $dateRange = array_combine($dateRange, $dateLabels);
    
    $this->setWidget('date_range', new sfWidgetFormChoice(array(
        'choices' => $dateRange,
        'label' => 'For ',
        'default' =>  sfConfig::get('app_event_filter_max_date_range', 31)
    )));
    $this->setValidator('date_range', new sfValidatorChoice(array(
        'choices' => $dateRange
    )));
    
    $this->getWidgetSchema()->setNameFormat('event_dates[%s]');
  }
}
