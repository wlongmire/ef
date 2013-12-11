<?php
sfConfig::add(array(
    'doctrine_model_builder_options' => array('baseClassName' => 'wfDoctrineRecord')
));

$manager = Doctrine_Manager::getInstance();
$manager->registerValidators(array('nohtml', 'daterange', 'timerange', 'exclude', 'url'));
$manager->setAttribute(Doctrine::ATTR_TABLE_CLASS, 'Wf_Doctrine_Table');
$manager->setAttribute(Doctrine::ATTR_QUERY_CLASS, 'Wf_Doctrine_Query');
if (function_exists('apc_store') && ini_get('apc.enabled'))
{
  $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, new Doctrine_Cache_Apc(array(
    'prefix' => wfToolkit::hash(sfConfig::get('sf_root_dir'))
  )));
}