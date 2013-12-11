<?php

require_once dirname(__FILE__).'/../lib/siteAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/siteAdminGeneratorHelper.class.php';/**
 * 
 * siteAdmin actions.
 * @package    eventsfilter
 * @subpackage siteAdmin
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class siteAdminActions extends autoSiteAdminActions
{
  public function preExecute()
  {
    parent::preExecute();
    $this->forward404If(!$this->getUser()->isSuperadmin());
  }
  
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $this->getUser()->setFlash('success', $form->getObject()->isNew() ? $this->__('The item was created successfully.', null, 'apostrophe') : $this->__('The item was updated successfully.', null, 'apostrophe'));

      $site = $form->save();

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $site)));

      $params = array();
      
      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('success', $this->getUser()->getFlash('success').' ' . $this->__('You can add another one below.', null, 'apostrophe'));

        $this->redirect(aUrl::addParams($this->generateUrl('site_admin_new'), $params));
      }
      elseif ($request->hasParameter('_save'))
      {
        $this->redirect(aUrl::addParams($this->generateUrl('site_admin_edit', array('id' => $site->getId())), $params));
      }
      // The default is _save_and_list
      else
      {
        $this->getUser()->setFlash('success', $this->getUser()->getFlash('success'));

        $this->redirect(aUrl::addParams($this->generateUrl('site_admin'), $params));
      }
    }
    else
    {
      $this->getUser()->setFlash('error', $this->__('The item has not been saved due to some errors.', null, 'apostrophe'));
    }
  }
}
