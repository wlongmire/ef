<?php

/**
 * Description of myWidgetFormDoctrineChoiceNestedSet
 *
 * @author jeremy
 */
class myWidgetFormDoctrineChoiceNestedSet extends wfWidgetFormDoctrineChoice 
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('add_empty', 'None (Top-level)');
    $this->addOption('method', 'getIndentedName');
  }
  
  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoiceObjects()
  {
    if (!isset($this->choiceObjects))
    {
      if (is_null($this->getOption('table_method')))
      {
        $query = null === $this->getOption('query') ? 
                    Doctrine_Core::getTable($this->getOption('model'))->getTree()->createQueryWithRoot() : 
                    $this->getOption('query');
        if ($order = $this->getOption('order_by'))
        {
          $query->orderBy('root.' . $order[0] . ' ' . $order[1]);
        }      
        $query->addOrderBy($query->getRootAlias() . '.root_id ASC, ' . $query->getRootAlias() . '.lft ASC');        
        $this->setOption('query', $query);
      }
    }
    return parent::getChoiceObjects();
  }
}
