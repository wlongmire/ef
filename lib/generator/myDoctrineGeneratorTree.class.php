<?php

/**
 * Description of myDoctrineGeneratorTree
 *
 * @author jeremy
 */
class myDoctrineGeneratorTree extends sfDoctrineGenerator 
{
  public function renderField($field)
  {
    $renderedField = parent::renderField($field);
//    if($field->getName() == 'name')
//    {
//      $renderedField = 
//    }
    return $renderedField;
  } 
  
  public function getTreeColumn()
  {
    return $this->params['tree_column'];
  }
}
