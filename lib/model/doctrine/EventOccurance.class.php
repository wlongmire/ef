<?php

/**
 * EventOccurance
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    eventsfilter
 * @subpackage model
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class EventOccurance extends BaseEventOccurance
{  
  public function getApiData() {
    return(array('start_date'=>$this->start_date, 'end_date'=>$this->end_date, 'start_time'=>$this->start_time, 'end_time'=>$this->end_time, 'ticket_url'=>$this->ticket_url));
  }
}
