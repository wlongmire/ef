<?php

/**
 * sfGuardUser form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
  public function configure()
  {
    $this->originalGroups = array();
    parent::configure();
    unset($this['full_name']);
    $this->setWidget('first_name', new sfWidgetFormInputText());
    $this->setWidget('last_name', new sfWidgetFormInputText());
    
    if (isset($this['groups_list']))
    {
      $this->configureGroups();
    }

    if (isset($this['password']))
    {
      $this->setWidget('password', new sfWidgetFormInputPassword());
      $this->getValidator('password')->setOption('min_length', 6);
      $this->setWidget('password_again', clone $this->getWidget('password'));
      $this->setValidator('password_again', clone $this->getValidator('password'));
      $this->widgetSchema->moveField('password_again', 'after', 'password');
      $this->mergePostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_again', array(), 
                                  array('invalid' => 'The two passwords must be the same.')));
    }
    $this->disableAutocomplete();    
  }
  

  protected function doSave($con = null) 
  {
    $this->savePermissionsList($con);
    $this->saveCategoriesList($con);
    $this->saveBlogEditorItemsList($con);
    $this->saveOwnedProfilesList($con);
    $this->saveOwnedEventsList($con);
    $this->saveOwnedVenuesList($con);

    parent::doSave($con);
    
    if (isset($this['groups_list']))
    {
      $this->saveGroupsList($con);      
    }
  }
  
  protected function doUpdateObject($values)
  {
    if (isset($this['groups_list']))
    {
      $this->selectedGroups = (array)$values['groups_list'];
      unset($values['groups_list']);
    }
    return parent::doUpdateObject($values);
  }
  
  public function updateDefaultsFromObject() 
  {
    parent::updateDefaultsFromObject();
    if (isset($this['groups_list']) && Site::current() && $this->object->exists())
    {
      $site = Site::current();
      $this->originalGroups = sfGuardGroupTable::findGroupsForUserAndSite($this->object, $site);
      $this->setDefault('groups_list', $this->originalGroups->getPrimaryKeys());
    }
  }
  
   protected function configureGroups()
  {
    $this->groups = sfGuardGroupTable::getInstance()->findAll();
    $choices = array();
    $helps = array();
    foreach($this->groups as $group)
    {
      $choices[$group->id] = ucwords(str_replace('_', ' ', $group->name));
    }
    $this->setWidget('groups_list', new myWidgetFormSelectCheckbox(array(
        'choices' => $choices,
        'label' => 'Groups'
    )));
    $this->setValidator('groups_list', new sfValidatorChoice(array(
        'choices' => array_keys($choices),
        'multiple' => true,
        'required' => false
    )));
    $this->getWidgetSchema()->moveField('groups_list', sfWidgetFormSchema::FIRST);
  }
  
  /**
   * Overridden to not remove groups not visible to the current user
   * @param Doctrine_Connection $con
   * @param array $values
   */
  public function saveGroupsList($con = null)
  {
    if (!isset($this['groups_list']))
    {
      return;
    }
    
    $site = Site::current();
    if ($this->originalGroups && $this->originalGroups->count())
    {
      $existing = $this->originalGroups->getPrimaryKeys();
    }
    else
    {
      $existing = array();
    }

    $unlink = array_diff($existing, $this->selectedGroups);
    $unlink = array_intersect($this->groups->getPrimaryKeys(), $unlink); //reduce unlink to only include items that were present
    if (count($unlink))
    {
      foreach($this->object->sfGuardUserGroup as $index => $gug)
      {
        if ($gug->site_id == $site->id && in_array($gug->group_id, $unlink))
        {
          $this->object->sfGuardUserGroup->remove($index);
          $gug->delete();
        }
      }
    }

    $link = array_diff($this->selectedGroups, $existing);

    if (count($link))
    {
      foreach($link as $groupId)
      {
        $gug = new sfGuardUserGroup();
        $gug->site_id = $site->id;
        $gug->group_id = $groupId;
        $gug->user_id = $this->object->id;
        $gug->save();
      }
    }
    
    $this->object->refreshRelated('sfGuardUserGroup');
  }
}
