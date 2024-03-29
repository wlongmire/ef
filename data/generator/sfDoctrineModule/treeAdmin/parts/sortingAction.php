  protected function addSortQuery($query)
  {
    if (array(null, null) != ($sort = $this->getSort()))
    {
      if (!in_array(strtolower($sort[1]), array('asc', 'desc')))
      {
        $sort[1] = 'asc';
      }
      
      $query->leftJoin($query->getRootAlias() . '.Root root');
      $query->addOrderBy('root.' . $sort[0] . ' ' . $sort[1]);
    }
    
    $query->addOrderBy('root_id ASC');
    $query->addOrderBy('lft ASC');
  }

  protected function getSort()
  {
    if (!is_null($sort = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module')))
    {
      return $sort;
    }

    $this->setSort($this->configuration->getDefaultSort());

    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', null, 'admin_module');
  }

  protected function setSort(array $sort)
  {
    if (!is_null($sort[0]) && is_null($sort[1]))
    {
      $sort[1] = 'asc';
    }

    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.sort', $sort, 'admin_module');
  }
