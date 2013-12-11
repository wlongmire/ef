<?php

/**
 * Description of myWidgetFormFilterTree
 *
 * @author jeremy
 */
class myWidgetFormFilterTree extends sfWidgetForm
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('tree');    
    $this->addOption('column', 'name');    
    $this->addOption('multiple', false);
    $this->addOption('model', null);
    $this->addOption('title', false);
    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');    
    $tree = $this->getOption('tree');
    $first = reset($tree);
    $currentLevel = $first ? $first['level'] : 0;
    return get_partial('default/filterTree', array(
        'widget' => $this,        
        'type' => $this->getOption('multiple') ? 'checkbox' : 'radio',
        'name' => $name,
        'column' => $this->getOption('column'),
        'active' => $value,
        'model' => $this->getOption('model'),
        'title' => $this->getOption('title'),
        'tree' => $tree,
        'currentLevel' => $currentLevel
    ));
  }
 
//  removed for dependency issues in production
//  public function getJavaScripts() 
//  {
//    return array('page/filter.js');
//  }
}
