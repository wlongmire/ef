<?php
/**
 * @package    apostrophePlugin
 * @subpackage    form
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class aTextForm extends BaseaTextForm
{
  /**
   * DOCUMENT ME
   */
  public function configure()
  {
    parent::configure();
    if (isset($this->soptions['use_phlocal_nav_validator']) && $this->soptions['use_phlocal_nav_validator'])
    {
    }
  }
}