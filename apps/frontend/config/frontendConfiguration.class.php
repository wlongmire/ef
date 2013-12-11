<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('template.filter_parameters', array($this, 'filterTemplateParameters'));
  }
  
  public function filterTemplateParameters(sfEvent $event, $parameters)
  {
    $parameters['_site'] = Site::current();
    return $parameters;
  }
}
