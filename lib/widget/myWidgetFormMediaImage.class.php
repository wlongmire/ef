<?php

/**
 * Description of myWidgetFormMediaImage
 *
 * @author jeremy
 */
class myWidgetFormMediaImage extends sfWidgetForm
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('object');
    $this->addRequiredOption('return_url');
    $this->addOption('relation', 'Picture');
    $this->addOption('new_message', 'You\'ll be able to select an image after the item is saved.');
  }
  
  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $object = $this->getOption('object');
    if ($object->exists())
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('Partial', 'a')); 
      $hasImage = (boolean)$object->media_item_id;
      $label = $hasImage ? 'Edit Image' : 'Choose an Image';            
      $parts = array();
      
      $url = sfContext::getInstance()->getRouting()->generate('a_media_item_set', array(
          'id' => $object->getPrimaryKey(), 
          'table' => get_class($object)
      ));
      $url .= '?return_url=' . $this->getOption('return_url');
      $parts['button'] = sprintf('<div class="clearfix"><a href="%s" class="a-btn a-ui icon a-edit" target="_blank"><span class="icon"></span>%s</a></div>', $url, $label);      
      
      if ($hasImage)
      {
        $parts['image'] = get_partial('aMedia/image', array(
          'item' => $object->{$this->getOption('relation')},
          'variant' => 'detail'
        ));        
      }
      
      $parts['help'] = '<div class="a-help">The image is saved independently from the form.</div>';
      return implode("\n", $parts);
    }
    else
    {
      return '<div class="a-help">' . $this->getOption('new_message') . '</div>';
    }
  }
}