<?php

/**
 * Base project form.
 * 
 * @package    eventsfilter
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends wfFormSymfony
{
  public function debug()
  {
    parent::printDebugInfo();
  }
}
