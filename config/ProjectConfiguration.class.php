<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  { 
    set_include_path(sfConfig::get('sf_lib_dir') . '/vendor' . PATH_SEPARATOR . get_include_path());
        
    $this->enablePlugins(array(
      'sfDoctrinePlugin',
      'sfDoctrineGuardPlugin',
      'apostrophePlugin',
      'apostropheBlogPlugin',
      'sfDoctrineActAsTaggablePlugin',
//      'sfAjaxDebugPlugin',
      'wfAddonsPlugin',
      'wfApostropheAddonsPlugin',
      'wfJqueryWidgetsPlugin',
      'fsOverseerPlugin'
    ));
    
    sfWidgetFormSchema::setDefaultFormFormatterName('AAdmin');
  }
}
