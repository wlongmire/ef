<?php
/*
 */
class Doctrine_Tree_MyNestedSet extends Doctrine_Tree_NestedSet
{
  public function findAllTrees($orderBy = null)
  {
    $rootIds = $this->findAllRootIdsByName($orderBy);

    $tree= array();
    foreach ($rootIds as $rootId) 
    {
      $tree = array_merge($tree, $this->fetchTree(array('root_id' => $rootId), Doctrine_Core::HYDRATE_ARRAY));
    }
    
    return $tree;    
  }
  
  public function findSortedTrees($column = 'name', $andWhere = false, Doctrine_Query $query = null)
  {
    $query = is_null($query) ? $this->getBaseQuery() : $query;
    $query->orderBy('root_id ASC, lft ASC')
      ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
    
    if ($andWhere)
    {
      $query->andWhere($andWhere);
    }
                
    $nodes = $query->execute();

    $trees = array();    
    $ancestorNames = array();
    $lastAncestorName = '';
    $lastLevel = 0;
    foreach($nodes as $node)
    {
      if ($node['level'] > $lastLevel)
      {
        $ancestorNames[] = $lastAncestorName;
      }
      elseif ($node['level'] < $lastLevel)
      {
        $ancestorNames = array_slice($ancestorNames, 0, $node['level'] - $lastLevel); 
      }
      $lastLevel = $node['level'];
      $lastAncestorName = $node[$column];
      $trees[implode('', $ancestorNames) . $node[$column]] = $node;
    }

    ksort($trees); //this fails on matching substrings between 
    
    return $trees;    
  }
  
  public function findAllRootIdsByName($orderBy = null)
  {
    $query = $this->table->createQuery('r')
        ->select('r.id')
        ->where('r.lft = ?', 1);
    
    if ($orderBy)
    {
      $query->orderBy($orderBy);
    }

    $ids = $query
      ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
      ->execute();
    
    return wfToolkit::arrayPluck($ids, 'id'); 
  }
  
  public function createQueryWithRoot()
  {
    $q = Doctrine_Core::getTable($this->getBaseComponent())
          ->createQuery('r')
          ->leftJoin('r.Root root');
    return $q;    
  }

}
