<?php

/**
 * Description of myFilterActions
 * 
 * Common logic for objects that do filtering
 *
 * @author jeremy
 */
abstract class myFilterActions extends wfActions
{
  protected $filterTypes = array(),
            $model = '';
  
  abstract protected function getIndexRoute();
  abstract protected function doPostFilter(sfWebRequest $request);
  abstract public function executeIndex(wfWebRequest $request);
  
  protected function isExclusiveFilter($filter)
  {
    return in_array($filter, $this->exclusiveFilters);
  }
  
  protected function isMultiFilter($filter)
  {
    return isset($this->filterTypes[$filter]) && $this->filterTypes[$filter] == 'multi-table';
  }
  
  public function executeClear(sfWebRequest $request)
  {
    $this->redirectIf(!$request->isXmlHttpRequest(), $this->generateUrl($this->getIndexRoute()));
    $this->forward($this->getModuleName(), 'index');
  }
  
  public function executePermalink(sfWebRequest $request)
  {
    $filters = array_intersect_key($request->getGetParameters(), $this->filterTypes);
    $this->setFilters($filters);
    if ($request['iframe'])
    {
      $this->executeIndex($request);
      $this->setTemplate('index');
      return sfView::SUCCESS;
    }
    $request['permalink'] = true;
    $this->forward($this->getModuleName(), 'index');
  }
  
  public function executeToggleImages(sfWebRequest $request)
  {
    $key = $this->model . '.show_images';
    $this->getUser()->setAttribute($key, isset($request['on']) ? $request['on'] : !$this->getUser()->getAttribute($key));
    $this->redirect($this->generateUrl($this->getIndexRoute()));
  }
  
  protected function getDefaultFilters()
  {
    return array();
  }
  
  protected function getFilterAttributeValues(){
    return array_merge($this->getDefaultFilters(), $this->getUser()->getAttribute($this->model . '.filters', array())); //merge so any defaults are always present    
  }
  
  protected function toggleFilter(sfWebRequest $request)
  {
    $this->setFilter($request['filter'], $request->getPostParameter('value', $request->getParameter('value'))); //make post parameters take precedence  )
  }
  
  public function executeAddFilter(sfWebRequest $request)
  {
    $filter = $request['filter'];
    $value = $request->getPostParameter('value', $request->getParameter('value'));    
    if ($this->isMultiFilter($filter))
    {
      if ($value)
      {
        $filters = $this->getFilterAttributeValues();         
        if (isset($filters[$filter]) && $filters[$filter] && !is_array($filters[$filter]))
        {
          $filters[$filter] = array($filters[$filter]);
        }
        $filters[$filter][$request['subfilter']] = $value;
        $this->setFilters($filters);                      
      }
    }
    else //it is a single filter
    {
      $this->setFilter($filter, $value);      
    }     
    
    $this->doPostFilter($request);
  }
  
  public function executeToggleFilter(sfWebRequest $request)
  {
    $this->toggleFilter($request);
    $this->doPostFilter($request);
  }
  
  public function setFilter($filter, $value, $checkExclusive = true)
  {
    if (array_key_exists($filter, $this->filterTypes))
    {  
      $filters = $this->getFilterAttributeValues(); 
      if (!$value)
      {         
        if (!$value && !$this->isExclusiveFilter($filter))
        {
          $filters[$filter] = 0;
        }
        else
        {
          unset($filters[$filter]);            
        }
      }
      else
      {
        $filters[$filter] = $value;
      }
      
      $this->setFilters($filters);
    }    
  }
  
  protected function buildFilters()
  {
    $filters = array();
    $filterValues = array_intersect_key($this->getFilterAttributeValues(), $this->filterTypes); //only valid filters, please
    
    foreach($filterValues as $filter => $value)
    {
      $filters[$filter] = $this->buildFilterType($this->filterTypes[$filter], $filter, $value);
    }
    
    foreach(Site::current()->getFallbackFilters($this->model) as $filter => $value)
    {
      if (!isset($filters[$filter]) || !$filters[$filter])
      {
        $filters[$filter] = $value;
      }
    }
    
    return $filters;
  }
  
  protected function buildFilterType($filterType, $filter, $value)
  {
    switch($filterType)
    {
      case 'date':
        return new DateTime($value);
      case 'table':
        return $value && !($value instanceof Doctrine_Record) ? 
          Doctrine_Core::getTable(ucfirst(sfInflector::classify($filter)))->findObjectForFilter($value) :
          $value;
      case 'multi-table':
        return $value && !($value instanceof Doctrine_Collection) ? 
          Doctrine_Core::getTable(ucfirst(sfInflector::classify($filter)))->findObjectsForFilter(array_values((array)$value)) :
          $value;        
      case 'boolean':
        return (boolean)$value;
      default:
        return $value;
    }
  }
  
  protected function setFilters($filters)
  {
    $this->getUser()->setAttribute($this->model . '.filters', array_merge($this->getDefaultFilters(), $filters)); //defaults can never be unset        
  }
}