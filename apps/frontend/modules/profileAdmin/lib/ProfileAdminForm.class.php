<?php

/**
 * Description of ProfileAdminForm
 *
 * @author jeremy
 */
class ProfileAdminForm extends ProfileForm
{
  public function configure()
  {
    $this->setOption('with_tags', true);
    $this->configureWebsites();
    
    $this->setWidget('location_id', new myWidgetFormFilterTree(array(
        'tree' => LocationTable::getInstance()->getTree()->findSortedTrees()
    )));   
    
    parent::configure();
    
    $this->getWidget('categories_list')->setOption('multiple', true);
    
    $this->setWidget('media_item_id', new myWidgetFormMediaImageSimple(array(
        'object' => $this->object,
        'label' => 'Photo'
    )));
    $this->setValidator('media_item_id', new myValidatorFileMediaImageSimple(array(
        'required' => false
    )));
    $this->getWidgetSchema()->setHelp('media_item_id', 'Only <strong>one</strong> images is displayed in profile listing. Square images look best.');
    
    if ($this->object->is_group)
    {
      unset($this['groups_list']);
      $this->setWidget('members_list', new wfWidgetFormJqueryDoctrineSelectMany(array(
         'model' => 'Profile',
         'current_header' => 'Member List',
         'autocomplete_header' => 'Add Members',
         'autocomplete_options' => array('query_options' => array('is_group' => 0)),
         'label' => 'Group Members',
         'query' => ProfileTable::buildQueryForLiveSearch()
      )));
      $this->getValidator('members_list')->setOption('query', ProfileTable::buildQueryForGroupStatus(false));
      $this->getWidgetSchema()->setHelp('is_group', 'Changing group status will automatically delete all group members. Save change to see group memberships.');
      $this->getWidgetSchema()->moveField('members_list', 'after', 'is_group');
    }
    else
    {
      unset($this['members_list']);
      $this->setWidget('groups_list', new wfWidgetFormJqueryDoctrineSelectMany(array(
         'model' => 'Profile',
         'current_header' => 'Groups',
         'autocomplete_header' => 'Search Groups',
         'autocomplete_options' => array('query_options' => array('is_group' => 1)),
         'label' => 'Group Memberships',
         'query' => ProfileTable::buildQueryForLiveSearch()
      )));
      $this->getValidator('groups_list')->setOption('query', ProfileTable::buildQueryForGroupStatus(true));      
      $this->getWidgetSchema()->setHelp('is_group', 'Changing group status will automatically delete all group memberships. Save changes to see group members.');
      $this->getWidgetSchema()->moveField('groups_list', 'after', 'is_group');
    }      
    
    if (!sfContext::getInstance()->getUser()->hasCredential('admin'))
    {
      unset($this['is_group']);
    }
   
    
    $this->setWidget('owners_list', new wfWidgetFormJqueryDoctrineSelectMany(array(
       'model' => 'sfGuardUser',
       'current_header' => 'Accounts',
       'autocomplete_header' => 'Search For Accounts',
       'label' => 'Who can manage?',
       'query' => sfGuardUserTable::buildQueryForLiveSearch()
    )));    
    $this->getWidgetSchema()->moveField('owners_list', sfWidgetFormSchema::LAST);    
  }
    
  protected function processUploadedFile($field, $filename = null, $values = null)
  {
    if ($field == 'media_item_id' && $values[$field] instanceof sfValidatedFile)
    {
      $this->file = $values[$field];
      $this->item = new aMediaItem();
      $this->item->title = ($this->getObject()->getName() ?: 'Unknown') . ' Event Picture';
    }
  }
    
  protected function doUpdateObject($values) 
  {
    if (isset($this->item))
    {
      $this->item->preSaveFile($this->file);
      $this->item->save();
      $this->item->saveFile($this->file);       
      $values['media_item_id'] = $this->item->id;
    }
    else
    {
      $values['media_item_id'] = $this->object->media_item_id;
    }    
    
    if (isset($values['is_group']))
    {
      if ($values['is_group'])
      {
        unset($values['groups_list']);
      }
      if (!$values['is_group'])
      {
        unset($values['members_list']);
      }      
    }

    parent::doUpdateObject($values);
    
    if ($this->object->is_group)
    {
      $this->object->unlink('Groups');
    }
    else
    {
      $this->object->unlink('Members');
    }
  }
}
