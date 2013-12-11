  protected function canUserManage(<?php echo $this->getModelClass() ?> $r)
  {
    if ($this->getUser()->hasCredential('admin'))
    {
      return true;
    }
    $userId = $this->getUser()->getGuardUser()->getId();
    foreach ($r-><?php echo $this->getModelClass() ?>Owner as $ro)
    {
      if ($ro->user_id == $userId)
      {
        return true;
      }
    }
    return false;
  }

  protected function addOwnerRestrictionToQuery(Doctrine_Query $query)
  {
    if (!$this->getUser()->hasCredential('admin'))
    {
      $query->innerJoin($query->getRootAlias() . '.<?php echo $this->getModelClass() ?>Owner ro WITH ro.user_id = ?', 
        $this->getUser()->getGuardUser()->getId());    
    }
    return $query;
  }
  
  protected function addUserAsOwner(<?php echo $this->getModelClass() ?> $r)
  {
    if (!$this->getUser()->hasCredential('admin'))
    {
      $r->Owners->add($this->getUser()->getGuardUser());
    }
  }