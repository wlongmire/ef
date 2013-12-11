<?php

/**
 * EventTypeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EventTypeTable extends Wf_Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object EventTypeTable
   */
  public static function getInstance() 
  {
    return Doctrine_Core::getTable('EventType');
  }

  public function findObjectForFilter($id)
  {
    return static::getInstance()->findOneById($id);
  }
  
  public function findGalleryTreeIds()
  {
    $eventType = $this->findObjectForFilter(sfConfig::get('app_event_type_gallery_id'));
    return $eventType->getNode()->getCachedDescendantIds(null, true);
  }
  
  public function buildQueryForEventIds(Doctrine_Query $query, array $eventIds)
  {
    $rootAlias = $query->getRootAlias();
    $query->innerJoin($rootAlias. '.Event ev')
      ->andWhereIn('ev.id', $eventIds);
  }  
  
  public static function findForSite(Site $site)
  {
    $query = Doctrine_Query::create()
      ->from('EventType et INDEXBY et.id INNER JOIN et.Root etr')
      ->addSelect('et.*')
      ->andWhere('et.root_id != 11')
      ->orderBy('etr.name ASC, etr.root_id, et.level, et.name'); 
    return $query->execute();
  }
}