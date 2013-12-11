<?php

/**
 * sfGuardUserTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardUserTable extends PluginsfGuardUserTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object sfGuardUserTable
     */
  public static function getInstance() 
  {
    return Doctrine_Core::getTable('sfGuardUser');
  }
  
  /*
   * User joined with Profile
   * @return Doctrine_Query
   */
  public static function buildQueryForLiveSearch(Doctrine_Query $q = null)
  {
    return static::getInstance()->createQuery('gu')->orderBy('gu.first_name ASC, gu.last_name ASC');
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
    $query = static::buildQueryForLiveSearch()
      ->select('gu.id, gu.id AS key, gu.full_name AS name, gu.full_name AS label') //should match sfGuardUser::getLiveSearchName
      ->addWhere('gu.full_name LIKE ?', '%' . $query . '%')
      ->orWhere('gu.email_address LIKE ?', $query . '%')
      ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
      ->limit(10);

    return $query->execute();
  }
}