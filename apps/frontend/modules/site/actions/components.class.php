<?php

/**
 * Description of components
 *
 * @author jeremy
 */
class siteComponents extends sfComponents
{
  public function executeNavigation(sfWebRequest $request)
  {
    aTools::globalSetup(array('type' => 'aText', 'singleton' => true, 'slug' => Site::current()->getGlobalVirtualPageSlug()));
    $page = aTools::getCurrentPage();
    $slot = $page->getSlot('phlocal-nav');
    $text = $slot && $slot->type == 'aText' ? strip_tags($slot->value) : '';
    aTools::globalShutdown();
    
    $routingOptions = $this->getContext()->getRouting()->getOptions();
    $this->prefix = $routingOptions['context']['prefix'];
    $this->active = -1;
    $this->activeUrl = str_replace($request->getUriPrefix() . $this->prefix, '', $request->getUri());
    
    $this->navigation = array();
    $lastIndex = -1;
    if ($text)
    {
      foreach(explode("\n", $text) as $index => $line)
      {
        $navItem = array('children' => array());
        $lineValues = str_getcsv(str_replace('&quot;', '"', trim($line)));
        while (count($lineValues) <= 4)
        {
          $lineValues[] = '';
        }
        list($navItem['url'], $navItem['title'], $navItem['list_title'], $enabledFilters) = $lineValues;
        $navItem['url'] = html_entity_decode($navItem['url']);
        $navItem['enabled_filters'] = $enabledFilters ? explode('&', html_entity_decode($enabledFilters)) : array();
        if (!$navItem['url'])
        {
          continue;
        }
        if ('>' == $navItem['url'][0])
        {
          $navItem['right-align'] = true;
          $navItem['url'] = ltrim($navItem['url'], '>');
        }
        if ('-' == $navItem['url'][0])
        {
          $navItem['url'] = ltrim($navItem['url'], '-');
          $this->navigation[$lastIndex]['children'][] = $navItem;
        }
        else
        {
          $this->navigation[] = $navItem;
          $lastIndex++;
        }

        if ($this->activeUrl == $navItem['url'] || (!$this->activeUrl && $index === 0))
        {
          $this->active = $lastIndex;
          sfConfig::set('local_filter_title', $navItem['list_title']);
          sfConfig::set('enabled_local_filters', $navItem['enabled_filters']);
        }      
      }
      
      if ($this->activeUrl == '/')
      {
        $this->active = 0;
        sfConfig::set('local_filter_title', $this->navigation[0]['list_title']);
        sfConfig::set('enabled_local_filters', $this->navigation[0]['enabled_filters']); 
      }      
    }
     
//    $this->navigation = array(
//        array('url' => '/event/permalink?date_range=1&event_type=2&discipline=1', 'title' => 'Exhibits', 'children' => array(
//            array('url' => '/event/permalink?date_range=1&event_type=2&discipline=1&location=25', 'title' => 'Center City' ),
//            array('url' => '/event/permalink?date_range=1&event_type=2&discipline=1&location=24', 'title' => 'Old City'),                    
//        )),
//        array('url' => '/profile/permalink?category=3&discipline=1&location=11', 'title' => 'Center City Artists', 'children' => false)
//    );
  }
  
  public function executeSingleFilter()
  {
    if (strpos($this->filter, 'tag') === 0)
    {
      list($this->filter, $this->label) = explode('=', $this->filter);
      $this->tree = TagTable::getInstance()->findGroupedForHeadingName($this->label, $this->eventIds, $this->profileIds);
      $this->subfilter = $this->label;
    }
    else
    {
      $class = ucfirst(wfToolkit::camelize($this->filter));
      if (!$this->active)
      {
        if ($class == 'Location')
        {
          $site = Site::current();
          $this->active = $site->location_id ? LocationTable::getInstance()->findOneById($site->location_id) : null;
        }
        elseif ($class == 'EventType')
        {
          $this->active = EventTypeTable::getInstance()->findOneById(sfConfig::get('app_event_type_default_subfilter_id'));    
        }
      }
      $table = Doctrine_Core::getTable($class);
      $query = $table->buildQueryForSubfilter($this->active, $this->profileIds, $this->eventIds);
      $this->tree = $table->getTree()->findSortedTrees('name', false, $query);
      $this->label = $class == 'Discipline' && $this->active ?
                        $this->active->name . ' Types' :
                        ucwords(str_replace('_', ' ', wfToolkit::underscore($this->filter)));
      if (in_array($this->label, array('Discipline', 'Category', 'Location', 'Event Type')))
      {
        $this->label = $this->label == 'Category' ? 'Categories' : $this->label . 's';
      }
      if ($class != 'Tag')
      {
        $this->label = 'All ' . $this->label;
      }      
      $this->subfilter = null;
    }
    if (!$this->tree)
    {
      return sfView::NONE;
    }
  }  
}