<?php

/**
 * default actions.
 *
 * @package    eventsfilter
 * @subpackage default
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
  public function executeError404(sfWebRequest $request)
  {
    
  }
  
  public function executeHome(sfWebRequest $request)
  {
//    $this->setLayout('filter');
  }
  
  public function executeCompileEmbedCss(sfWebRequest $request)
  {
    $this->getResponse()->setHttpHeader('Content-Type', 'text/css');
    $destination = sfConfig::get('sf_web_dir') . '/uploads/asset-cache/embed.css';
    aAssets::compileLessIfNeeded(sfConfig::get('sf_web_dir') . '/css/embed.less', $destination);
    return $this->renderText(file_get_contents($destination));
  }
 
  public function executeLoginAs(sfWebRequest $request)
  {
    $this->forward404If(!$this->getUser()->isSuperAdmin() && $_SERVER['REMOTE_ADDR'] != '127.0.0.1', 'Must be super admin.');

    if ($request->getMethod() == sfWebRequest::POST)
    {
      $search = trim($request['email']);
      $user = sfGuardUserTable::getInstance()->createQuery('gu')
                ->where('gu.email_address = ? OR gu.full_name = ?', array($search, $search))
                ->fetchOne();

      if ($user)
      {
        $this->getUser()->signin($user);
        $this->getUser()->setFlash('success', 'Signed in as %name%', array('%name%' => $user->full_name));
        $this->redirect('@homepage');
      }
      else
      {
        $this->getUser()->setFlash('error', 'User with email "' . $request['email'] . '" not found.');
      }
    }
  }
  
  public function executeTest(sfWebRequest $request)
  {
    $this->forward404If(fsTools::isProductionServer());
  }
}
