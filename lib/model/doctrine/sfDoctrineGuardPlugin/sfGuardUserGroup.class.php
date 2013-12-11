<?php

/**
 * sfGuardUserGroup
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    eventsfilter
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class sfGuardUserGroup extends PluginsfGuardUserGroup
{
  public function preSave($event) 
  {
    parent::preSave($event);
    if (!$this->site_id)
    {
      if ($this->site_id === 0)
      {
        $this->site_id = 1;
      }
      else
      {
        $this->site_id = Site::current()->id;
      }      
    }
  }
}
