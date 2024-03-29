<?php

/**
 * VenueTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class VenueTable extends Wf_Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object VenueTable
   */
  public static function getInstance() 
  {
    return Doctrine_Core::getTable('Venue');
  }

  public static function findObjectForShow($parameters)
  {
    $query = static::getInstance()->createQuery('vn');
    return $query
      ->where('vn.id = ?', $parameters['id'])
      ->execute();
  }
  
  /**
   * @return Venue
   */
  public function findObjectForFilter($id)
  {
    return static::getInstance()->findOneById($id);
  }
  
  public function buildQueryForFilters(array $filters = array())
  {
    $query = Doctrine_Query::create()->from('Venue v INDEXBY v.id')
      ->addSelect('v.name')
      ->addSelect('v.blurb')
      ->addSelect('v.blurb')
      ->addSelect('v.url')
      ->leftJoin('v.Picture pic')
      ->addSelect('pic.type, pic.format, pic.slug, pic.width, pic.height, pic.title')      
      ->leftJoin('v.Location lc')
      ->addSelect('lc.name, lc.root_id, lc.lft, lc.rgt')
      ->leftJoin('v.Event e')
      ->addSelect('e.id, e.name')
      ->innerJoin('e.EventOccurances eo')
      ->addSelect('eo.start_date, eo.end_date, eo.start_time, eo.end_time, eo.event_id, eo.ticket_url')
      ->groupBy('v.id')
      ->setHydrationMode(Doctrine::HYDRATE_ARRAY);

    if ($filters) {
      foreach($filters as $filter => $value)
      {
        $method = sprintf('add%sFilterToQuery', ucfirst(sfInflector::camelize($filter)));
        if ($value && method_exists($this, $method))
        {
          $this->$method($query, $value, $filters);
        }
      }
    }
    
    return $query;
  }

    /**
   * !!NOTE: findBySearchQuery receives UNSANITIZED input.
   * 
   * @param string $query
   * @param array $params None currently used
   * @return array Up to $limit Fields matching $query indexed by Field id
   */
  public static function findBySearchQuery($query, $params = array())
  {
    $query = Doctrine_Query::create()->from('Venue vn')
      ->select('vn.id, vn.id AS key, vn.name AS name, vn.name AS label') 
      ->addWhere('vn.name LIKE ?', '%' . $query . '%')
      ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
      ->limit(10);

    return $query->execute();
  }
}