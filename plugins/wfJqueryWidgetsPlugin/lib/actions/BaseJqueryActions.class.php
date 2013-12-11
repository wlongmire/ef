<?php
class BaseJqueryActions extends wfActions
{
  public function executeLiveSearch(sfWebRequest $request)
  {
    $this->requireXmlHttp();
    
    $table = Doctrine_Core::getTable($request['model']);
    $user = $this->getUser();
    if (method_exists($user, 'canLiveSearch'))
    {
      $this->isJSON();
      $this->forward404If(!$user->canLiveSearch($table), 'Current user not permitted to perform a live search on ' . $table);
    }

    $results = $table->findBySearchQuery($request['term'], $request->getParameterHolder()->getAll());
    return $this->renderJSON($results);
  }
}
