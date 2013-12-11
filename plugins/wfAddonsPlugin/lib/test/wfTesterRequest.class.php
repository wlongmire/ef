<?php
class wfTesterRequest extends sfTesterRequest
{
  public function isRequest($module, $action)
  {
    return $this->begin()->isParameter('module', $module)->isParameter('action', $action)->end();  
  }
  
  public function isPage($page)
  {
    return $this->isParameter('page', $page);
  }
  
  public function isOrderBy($orderBy)
  {
    return $this->isParameter('order', $orderBy);
  }
  
  public function isSortAsc()
  {
    return $this->isParameter('sort', 'asc');
  }
  
  public function isSortDesc()
  {
    return $this->isParameter('sort', 'desc');
  }

  public function debug()
  {
    print $this->tester->error('Request debug');

    echo '$_SERVER: ' . "\n";
    var_export($_SERVER);
    echo "\n";

    echo '$_GET: ' . "\n";
    var_export($_GET);
    echo "\n";

    echo '$_POST: ' . "\n";
    var_export($_POST);
    echo "\n";

    return $this;
  }
}