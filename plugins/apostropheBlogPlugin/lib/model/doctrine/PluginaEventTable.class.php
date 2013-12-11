<?php

/**
 * PluginaEventTable
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginaEventTable extends aBlogItemTable
{
  private static $engineCategoryCache;
  /**
   * Returns an instance of this class.
   *
   * @return object PluginaEventTable
   */
  public static function getInstance()
  {
    return Doctrine::getTable('aEvent');
  }

  public function createQuery($alias = '')
  {
    $query = parent::createQuery($alias);
    $query->orderBy($query->getRootAlias().'.start_date asc');
    $query->addOrderBy($query->getRootAlias().'.start_time asc');

    return $query;
  }

  public function filterByYMD($year=null, $month=null, $day=null, $q=null)
  {
    $rootAlias = $q->getRootAlias();

    $sYear = isset($year)? $year : 0;
    $sMonth = isset($month)? $month : 0;
    $sDay = isset($day)? $day : 0;
    $startDate = "$sYear-$sMonth-$sDay 00:00:00";

    $eYear = isset($year)? $year : 3000;
    $eMonth = isset($month)? $month : 12;
    $eDay = isset($day)? $day : 31;
    $endDate = "$eYear-$eMonth-$eDay 23:59:59";

    $q->addWhere("$rootAlias.start_date <= ? AND $rootAlias.end_date >= ?", array($endDate, $startDate));
  }

  public function addUpcoming(Doctrine_Query $q, $limit = null)
  {
    $q->orderBy('start_date');
    $q->addWhere('DATE(start_date) >= DATE(NOW()) OR DATE(end_date) >= DATE(NOW())');
    if(!is_null($limit))
      $q->limit($limit);
  }

  public function getEngineCategories()
  {
    return aEngineTools::getEngineCategories('aEvent');
  }
  
  public function getCountByCategory()
  {
    $raw = Doctrine::getTable('aCategory')->createQuery('c')->innerJoin('c.aBlogItemToCategory etc')->innerJoin('etc.BlogItem b WITH b.type = ?', 'event')->select('c.name, c.slug, count(etc.blog_item_id) as num')->groupBy('etc.category_id')->orderBy('c.name ASC')->execute(array(), Doctrine::HYDRATE_ARRAY);
    $results = array();
    foreach ($raw as $info)
    {
      $results[$info['id']] = array('name' => $info['name'], 'slug' => $info['slug'], 'count' => $info['num']);
    }
    return $results;
  }
}