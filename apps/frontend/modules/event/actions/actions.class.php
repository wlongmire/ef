<?php
/**
 * event actions.
 *
 * @package    eventsfilter
 * @subpackage event
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventActions extends myFilterActions
{
  protected $filterTypes = array(
      'discipline' => 'table', 
      'location' => 'table',
      'event_type' => 'table',
      'tag' => 'multi-table',
      'start_date' => 'date',
      'date_range' => 'none',
      'profile' => 'table',
      'include_members' => 'boolean',
      'include_past_events' => 'boolean',
      'venue' => 'table',
      'event' => 'table',
      'name' => 'string'      
  ),
    $exclusiveFilters = array('profile', 'venue', 'event'),
    $model = 'event';
  
  protected function getDefaultFilters()
  {
    return array(
        'start_date' => date('Y-m-d'),
        'date_range' => 7,
        'include_members' => false,
        'include_past_events' => false,
        'name' => ''
    );
  }
  
  public function executeMobileIndex()
  {
    $this->setLayout('mobile');
  }
  
  /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */

  public function executeIndex(wfWebRequest $request)
  {
    if ($request->isMobile() && Site::current() == "designphiladelphia"){
      $this->forward('event', 'mobileIndex');     
    }

    if (!$request['iframe'] && $this->getContext()->getActionStack()->getSize() == 1)
    {
      $filters = Site::current()->getDefaultFilters();
      foreach(array('images', 'sort') as $special)
      {
        if (isset($filters[$special]) && $filters[$special])
        {
          $request[$special] = $filters[$special];
        }
      }
      $this->setFilters(Site::current()->getDefaultFilters());
    }
    //below logic exists here because it's used seldomly, so it's faster to come here for the homepage
    if (Site::current()->mode == Site::MODE_ENTRY && 
          $this->getContext()->getRouting()->getCurrentRouteName() == 'homepage' &&
          !array_key_exists('filter-details', $this->getResponse()->getSlots()))
    {
      $this->forward('default', 'home');       
    }
    $this->filters = $this->buildFilters();
    $this->applied_filters = array_diff_assoc($this->getFilterAttributeValues(), $this->getDefaultFilters());                

    $this->events = $this->findForFilters($this->filters);
    $this->time_or_cost = (!$this->request['sort'] || $this->request['sort'] == 'date')
                             && EventTable::isPivotedCollectionWithTimeOrCost($this->events);      
    
    if ($request['format'] == 'json')
    {
      return $this->renderJSONP($this->events);
    }

    if ($request->isXmlHttpRequest() || $request['callback'])
    {
      $html = $this->getPartial('list', array(
          'filters' => $this->filters,
          'events' => $this->events,
          'time_or_cost' => $this->time_or_cost,
          'applied_filters' => $this->applied_filters
      ));
      
      if ($request['callback'])
      {
        return $this->renderJSONP(array('html' => $html));
      }
      
      return $this->renderText($html);
    }      
    
    $this->setLayout('filter');
  }
  
  public function executePaginate() 
  {
    $this->getUser()->setFlash('page_start_date', $request['date'], !$request->isXmlHttpRequest());
    $this->forwardIf($request->isXmlHttpRequest(), 'event', 'index');
    $this->redirect($this->generateUrl('event_index'));    
  }
  
  public function executeSetProfileWithPastHack(sfWebRequest $request)
  {
    $this->toggleFilter($request);
    $this->setFilter('include_past_events', true);
    $this->doPostFilter($request);
  }
  
  protected function doPostFilter(sfWebRequest $request)
  {
    $this->forwardIf($request->isXmlHttpRequest(), 'event', 'index');
    $this->redirect($this->generateUrl('event_index'));     
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->event = $this->getRoute()->getObject();
    if ($request['callback'])
    {
      return $this->renderJSONP(array(
          'html' => $this->getComponent('event', 'details', array(
            'event' => $this->event
          ))
      ));
    }
    if (!$request->isXmlHttpRequest())
    {
      $this->getResponse()->setSlot('filter-details', $this->getComponent('event', 'details', array('event' => $this->event)));
      $this->getResponse()->addMeta('description', myTools::blurbToMeta($this->event['blurb']));      
      $this->forward('event', 'index');
    }
  }
  
  public function executeApiShow(sfWebRequest $request)
  {
    $this->event = $this->getRoute()->getObject();
    $data = $this->event->getApiData();
    
    return $this->renderJSONP($data);
  }

  public function executeApiList(sfWebRequest $request)
  {
    $filters = array_intersect_key($request->getGetParameters(), $this->filterTypes);
    $this->setFilters(array_merge(Site::current()->getDefaultFilters(), $filters));
    $this->filters = $this->buildFilters();
    $this->events = $this->findForFilters($this->filters);
    return $this->renderJSONP($this->events);
  }
  
  protected function buildFilters()
  {
    $filters = parent::buildFilters();
    $filterValues = $this->getFilterAttributeValues();
    
    if ($filterValues['date_range'] > sfConfig::get('app_event_varied_max_date_range', 365))
    {
      $filterValues['date_range'] = 365;
    }
    
    //adjust dates
    $filters['max_date'] = clone $filters['start_date'];
    $filters['max_date']->modify('+' . ($filterValues['date_range'] - 1) . ' days');
    
    if ($pageDate = $this->getUser()->getFlash('page_start_date'))
    {
      $filters['start_date'] = new DateTime($pageDate);
    }
    
    $eventTable = EventTable::getInstance();
    $filters['paginate'] = false; //$eventTable->countForFilters($filters) > 10; //pagination has some issues
    
    if ($filters['paginate'])
    {
      $latestStartDate = new DateTime($eventTable->findLatestStartDateForFilters($filters));
      $filters['end_date'] = $filters['start_date'] > $latestStartDate ? clone $filters['start_date'] : $latestStartDate;
    }
    
    if (!isset($filters['end_date']) || $filters['end_date'] > $filters['max_date'])
    {
      $filters['end_date'] = clone $filters['max_date'];
    }
       
    if (isset($filters['profile']) && $filters['profile'])
    {
      if (!$this->request['iframe'])
      {
        unset($filters['event_type']);        
      }
      if ($filters['include_past_events'])
      {
        $filters['start_date'] = new DateTime('2010-01-01');
      }
    }
    else
    {
      $filters['include_past_events'] = false; //never past events unless we're restricted to a profile
    }

    return $filters;
  }
  
  protected function getIndexRoute()
  {
    return 'event_index';
  }
  
  protected function findForFilters($filters)
  {
    $query = EventTable::getInstance()->buildQueryForFilters($filters);
    if ($this->request['sort'] == 'alpha')
    {
      $query->orderBy('ev.name ASC');
    }
    elseif ($this->request['sort'] == 'cost')
    {
      $query->orderBy('ev.min_cost IS NOT NULL DESC, ev.min_cost ASC');
    }
    $unpivotedEvents = $query->execute();
    if ($unpivotedEvents)
    {
      ProfileTable::populateEvents($unpivotedEvents);
      DisciplineTable::populateEvents($unpivotedEvents);      
      $this->eventIds = wfToolkit::arrayPluck($unpivotedEvents, 'id');

      if ($this->request['sort'] && $this->request['sort'] != 'date')
      {
        return array(null => $unpivotedEvents);
      }
      
      $events = EventTable::getInstance()->pivotEventsForFilters($unpivotedEvents, $filters);
      return $events;
    }
    $this->eventIds = array();
    return array();
  }
}
