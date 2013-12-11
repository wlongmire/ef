<?php
class eventComponents extends sfComponents
{ 
  public function executeDetails(sfWebRequest $request)
  {
    $this->eventOccurances = array_keys(EventOccuranceTable::buildDateTimeEventMap($this->event->EventOccurances));
    $this->discipline = $this->event->Disciplines->getFirst();
    $this->tags = $this->event->getTags();
  }
  
  public function executeDateFilter()
  {
    $this->dateForm = new EventDateFilter();
    $this->dateForm->setDefaults(array(
        'start_date' => $this->filters['start_date']->format('m/d/Y'), 
        'date_range' => $this->filters['date_range']
    ));    
  }
  
  public function executeSearchFilter()
  {    
    $this->form = new EventSearchFilter();
    $this->form->setDefaults(array(
        'name' => isset($this->filters['name']) ? $this->filters['name'] : ''
    ));
  }
}