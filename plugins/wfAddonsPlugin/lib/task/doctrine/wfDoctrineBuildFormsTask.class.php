<?php

/**
 * Extended to set generator class
 */
class wfDoctrineBuildFormsTask extends sfDoctrineBuildFormsTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    parent::configure();
    $this->name = 'wf-build-forms';

    foreach($this->getOptions() as $option)
    {
      if ($option->getName() == 'generator-class')
      {
        $option->setDefault(false);
      }
    }
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!$options['generator-class'])
    {
      $options['generator-class'] = sfConfig::get('app_form_generator_form_generator_class');
    }
    parent::execute($arguments, $options);
  }
}