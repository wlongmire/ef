<?php

/**
 * sfGuardFormSignin for sfGuardAuth signin action
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 */
class sfGuardFormSignin extends BasesfGuardFormSignin
{
  /**
   * @see sfForm
   */
  public function configure()
  {
    $this->disableAutocomplete();
    $this->widgetSchema['username']->setLabel('E-Mail');
    $this->getWidgetSchema()->setLabel('remember', 'Stay signed in');
    $this->setDefault('remember', true);
  }
}
