<?php

/**
 * profile actions.
 *
 * @package    eventsfilter
 * @subpackage profile
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
  /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeNew(sfWebRequest $request)
  {
    if ($this->getUser()->isAuthenticated() && !$this->getUser()->hasCredential('admin'))
    {
      $this->redirect($this->generateUrl('homepage'));
    }
    
    $guardUser = new sfGuardUser();
    $this->profile = false;
    if ($request['claim_token'])
    {
      $this->profile = $this->getRoute()->getObject();       
      if ($this->profile)
      {
        $guardUser->Profile = $this->profile;
      }      
    }
 
    $this->form = new JoinForm($guardUser);
    if ($request->isMethod(sfWebRequest::POST))
    {
      if ($this->form->bindAndSave($request[$this->form->getName()]))
      {
        if ($this->profile && $this->profile->relatedExists('Picture'))
        {
          $this->profile->Picture->owner_id = $guardUser->id;
          $this->profile->Picture->save();
        }
        $this->getUser()->signin($this->form->getObject(), true);
        $this->getUser()->setFlash('success', 'Welcome to EventsFilter!');
        $this->redirect($this->generateUrl('homepage'));          
      }
    }
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $guardUser = $this->getUser()->getGuardUser();
        
    $this->editForm = new sfGuardUserEditForm($guardUser);
    $this->passwordForm = new sfGuardUserPasswordForm($guardUser);
    if ($request->isMethod(sfWebRequest::POST))
    {
      $formToSave = null;
      foreach(array($this->editForm, $this->passwordForm) as $form)
      {
        if ($request[$form->getName()])
        {
          $formToSave = $form;
          break;
        }        
      }
      if ($formToSave && $formToSave->bindAndSave($request[$formToSave->getName()]))
      {
        $this->getUser()->setFlash('success', 'Updated information.');
        $this->redirect($this->generateUrl('user_edit'));
      }
    }    
  }
}
