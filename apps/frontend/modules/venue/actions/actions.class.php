<?php

/**
 * venue actions.
 *
 * @package    eventsfilter
 * @subpackage venue
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class venueActions extends myFilterActions
{
   protected $filterTypes = array(
      'location' => 'table',
      'name' => 'string'
  ),
    $exclusiveFilters = array('venue'),
    $requiredFilters = array(),    
    $model = 'venue';
  
  protected function getDefaultFilters()
  {
    return array('name' => '');
  }
  
  public function executeIndex(wfWebRequest $request)
  {
    $this->filters = $this->buildFilters();
    $this->applied_filters = array_diff_assoc($this->getFilterAttributeValues(), $this->getDefaultFilters());
    $this->pager = new sfDoctrinePager('Venue', 100);

    $query = VenueTable::getInstance()->buildQueryForFilters($this->filters);

    $idQuery = clone $query;
    $this->profileIds = $idQuery->select('id')->removeQueryPart('orderby')->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR)->execute();
    $this->pager->setQuery($query);
    $this->pager->setPage($this->getUser()->getFlash('page'));
    $this->pager->init();

    $this->venue = $this->pager->getResults();

    if ($request->isXmlHttpRequest() || $request['callback'])
    {
      $html = $this->getPartial('list', array(
          'filters' => $this->filters,
          'pager' => $this->pager,
          'venues' => $this->venues,
          'applied_filters' => $this->applied_filters
      )); 

      if ($request['callback'])
      {
        if ($request['format'] == 'json')
        {
          return $this->renderJSONP($this->venues);
        }
        return $this->renderJSONP(array('html' => $html));
      }
      return $this->renderText($html);
    }      
    
    $this->setLayout('filter');
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->venue = $this->getRoute()->getObject();
    if ($request['callback'])
    {
      return $this->renderJSONP(array(
          'html' => $this->getComponent('venue', 'details', array(
            'venue' => $this->venue
          ))
      ));
    }
    if (!$request->isXmlHttpRequest())
    {
      $this->getResponse()->setSlot('filter-details', $this->getComponent('venue', 'details', array('venue' => $this->venue)));
      $this->getResponse()->addMeta('description', myTools::blurbToMeta($this->venue['blurb']));      
      $this->forward('event', 'index');
    }
  }

  public function executeApiShow(sfWebRequest $request)
  {
    $this->venue = $this->getRoute()->getObject();
    $data = $this->venue->getApiData();
    return $this->renderJSONP($data);
  }

  public function executeApiList(sfWebRequest $request)
  {
    $filters = array_intersect_key($request->getGetParameters(), $this->filterTypes);
    $this->setFilters(array_merge(Site::current()->getDefaultFilters(), $filters));
    $this->filters = $this->buildFilters();

    $this->pager = new sfDoctrinePager('Venue', 100);

    $query = VenueTable::getInstance()->buildQueryForFilters($this->filters);

    $this->pager->setQuery($query);
    $this->pager->setPage($this->getUser()->getFlash('page'));
    $this->pager->init();

    $this->venues = $this->pager->getResults();

    return $this->renderJSONP($this->venues);
  }


  protected function getIndexRoute()
  {
    return 'profile_index';
  }

  protected function doPostFilter(sfWebRequest $request)
  {
    $this->forwardIf($request->isXmlHttpRequest(), 'venue', 'index');
    $this->redirect($this->generateUrl('venue_index'));
  }
}
