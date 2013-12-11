<?php

/**
 * sfGuardUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    eventsfilter
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class sfGuardUser extends PluginsfGuardUser
{
  public function toParams()
  {
    return array('id' => $this->id, 'name' => $this->full_name);
  }
  
  public function preSave($event = null)
  {
    parent::preSave($event);
    $this->full_name = $this->first_name . ' ' . $this->last_name;
    if (!$this->username)
    {
      $this->username = $this->email_address;
    }
  }
}
