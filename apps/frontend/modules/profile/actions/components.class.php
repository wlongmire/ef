<?php
class profileComponents extends sfComponents
{  
  public function executeDetails()
  {
    $this->categories = $this->profile->Categories;
    $this->disciplines = $this->profile->Disciplines;
    $this->tags = $this->profile->getTags();
    $this->events = EventTable::getInstance()->findUpcomingByProfile($this->profile);
    $this->pastEvents = EventTable::getInstance()->findPastByProfile($this->profile);
    if ($this->profile->is_group)
    {
      $this->groupsOrMembers = ProfileTable::getInstance()->findGroupMembers($this->profile);
      $this->groupsOrMembersTitle = 'Members';
      $this->filterUrl = $this->generateUrl('profile_toggle_filter', 
                           array('filter' => 'profile', 'value' => $this->profile['id'], 'label' => myTools::urlify($this->profile['name'])));
    }
    else
    {
      $this->groupsOrMembers = ProfileTable::getInstance()->findGroupMemberships($this->profile);
      $this->groupsOrMembersTitle = 'Groups';
    }    
  }
  
  public function executeSearchFilter()
  {    
    $this->form = new ProfileSearchFilter();
    $this->form->setDefaults(array(
        'name' => isset($this->filters['name']) ? $this->filters['name'] : ''
    ));
  }
}