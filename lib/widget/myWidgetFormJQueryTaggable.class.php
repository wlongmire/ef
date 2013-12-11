<?php

class myWidgetFormJQueryTaggable extends sfWidgetFormInput
{	
	protected function configure($options = array(), $attributes = array())
	{
    $this->addRequiredOption('tag_headings');
		parent::configure($options, $attributes);
	}
  

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $render = array();
    $render[] = '<ul class="filter-tree checkbox widget multi-select removable">';    
    foreach($this->getOption('tag_headings') as $tagHeading)
    {
      if (count($tagHeading['Tag']))
      {
        $render[] = '<li class="expand-only"><label>' . $tagHeading['name'] . '</label>';
        $render[] = '<ul>';
        foreach($tagHeading['Tag'] as $tag)
        {
          $htmlId = $this->generateId($name . '[]', $tag['id']);
          $isActive = $value ? in_array($tag['name'], $value) : false;
          $render[] = sprintf('<li><input type="checkbox" name="%s[]" value="%s" id="%s" %s><label for="%s" %s>%s</label></li>',
                                  $name, $tag['name'], $htmlId, $isActive ? 'checked="checked"' : '', //input args
                                  $htmlId, $isActive ? 'class="active"' : '', $tag['name']);
        }       
        $render[] = '</ul></li>';
      }
    }
    $render[] = '</ul>';
    
    if (!sfConfig::get('filter_tree_js_included'))
    {
      sfConfig::set('filter_tree_js_included', true);
      a_js_call('ef.filterTrees(?)', '.filter-tree');
    }
    
    return implode("\n", $render);
  }
  
//  public function getJavaScripts() 
//  {
//    return array('page/filter.js');
//  }  
}