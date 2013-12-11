<?php
class venueComponents extends sfComponents
{
  public function executeDetails()
  {
    $this->events = EventTable::getInstance()->findUpcomingByVenue($this->venue);
  }
}