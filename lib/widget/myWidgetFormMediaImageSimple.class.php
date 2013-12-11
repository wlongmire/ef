<?php

/**
 * Description of myWidgetFormMediaImage
 *
 * @author jeremy
 */
class myWidgetFormMediaImageSimple extends sfWidgetFormInputFile
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addRequiredOption('object');
    $this->addOption('relation', 'Picture');
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
    
    $parts = array();    
    
    $hasImage = (boolean)$object->relatedExists($this->getOption('relation'));
    if ($hasImage)
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('Partial', 'a'));
      $parts['image'] = get_partial('aMedia/image', array(
        'item' => $object->{$this->getOption('relation')},
        'variant' => 'detail'
      ));
      $parts['break'] = '<br/>';
    }
    
    $parts['input'] = parent::render($name, $value, $attributes, $errors);

    return implode("\n", $parts);
  }
}