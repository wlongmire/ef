<?php
/*
 * Required dummy class for implementation of our extended nested set behaviors   
 */
class Doctrine_Node_MyNestedSet extends Doctrine_Node_NestedSet
{ 
  protected $_cache = array();
  
  public function getParentId()
  {
    if (!$this->isValidNode() || $this->isRoot())
    {      
      return null;
    }
    
    $parent = $this->getParent();
    
    return $parent->id;
  }
  
  public function setParentId($parentId)
  {
    if ($parentId != $this->getParentId() || !$this->isValidNode())
    {
      if (!$parentId)
      {
        //save as a root
        if ($this->isValidNode())
        {
          $this->makeRoot($this->record->getPrimaryKey());
          $this->record->save();
        }
        else
        {
          $this->_tree->createRoot($this->record); //calls $this->record->save internally
        }
      }
      else
      {
        //form validation ensures an existing ID for $parentId
        $parent = $this->record->getTable()->find($parentId);
//        die($parent ? $parent->id : 'no parent');
        $method = $this->isValidNode() ? 'moveAsFirstChildOf' : 'insertAsFirstChildOf';
        $this->$method($parent); //calls $this->record->save internally
      }
    }    
  }
  
  public function getCachedDescendants($depth = null, $includeNode = false)
  {
    $key = ($depth ?: 'all') . '-' . ($includeNode ? '1' : '0');
    if (!array_key_exists($key, $this->_cache))
    {
      $this->_cache[$key] = $this->getDescendants($depth, $includeNode);
    }
    return $this->_cache[$key];
  }
  
  public function getCachedDescendantIds($depth = null, $includeNode = false)
  {
    $descendants = $this->getCachedDescendants($depth, $includeNode);
    return $descendants ? 
      wfToolkit::arrayPluck($descendants, $this->record->getTable()->getIdentifier()) :
      array();
  }  
}
