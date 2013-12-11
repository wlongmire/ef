<?php
/**
 * Small modification to sfDoctrinePager to cache results. Cache is invalidated when the query is set.
 */
class wfDoctrinePager extends sfDoctrinePager
{
  protected $cachedResults = null;
  
  /**
   * Set query object for the pager. This will invalidate any cached results.
   *
   * @param Doctrine_Query $query
   * @return void
   */
  public function setQuery($query)
  {
    $this->cachedResults = null;
    $this->query = $query;
  }
  
  /**
   * Get all the results for the pager instance. If the results have been retrieved previous and the query hasn't been changed, cached results will be returned.
   * @param integer $fetchtype Doctrine::HYDRATE_* constants
   * @return Doctrine_Collection
   */
  public function getResults($hydrationMode = Doctrine::HYDRATE_RECORD)
  {
    if (isset($this->cachedResults) == false)
    {
      $this->cachedResults = parent::getResults($hydrationMode);
    }
    return $this->cachedResults;
  }
}
?>