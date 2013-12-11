<?php

class mySiteFilter extends sfFilter
{    
  /**
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    Site::setCurrent($this->getContext()->getRequest()->getEffectiveUrl());
    if ($this->isFirstCall())
    {
      $user = $this->getContext()->getUser();
      $user->clearCredentials();
      if ($user->isAuthenticated())
      {
        $user->addCredentials(sfGuardUserPermissionTable::findPermissionNamesForUser($user->getGuardUser(), Site::current()));
      }
    }
    $filterChain->execute();
  }
}
