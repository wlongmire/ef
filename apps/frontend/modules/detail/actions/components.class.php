<?php

/**
 * Description of components
 *
 * @author jeremy
 */
class detailComponents extends sfComponents 
{
  public function executeRelationTree()
  {
    if (!isset($this->ancestors))
    {
      $this->ancestors = $this->record->getNode()->getAncestors();
    }
  }
}
