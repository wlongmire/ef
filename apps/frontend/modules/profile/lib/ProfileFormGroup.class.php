<?php

/**
 * Description of ProfileUserForm
 *
 * @author jeremy
 */
class ProfileFormGroup extends ProfileForm 
{
  public function configure()
  {
    $this->useFields(array('members_list'));    
    
    parent::configure();
    
    $this->setWidget('members_list', new wfWidgetFormJqueryDoctrineSelectMany(array(
       'model' => 'Profile',
       'current_header' => 'Member List',
       'autocomplete_header' => 'Add Members',
       'autocomplete_options' => array('query_options' => array('is_group' => 0)),
       'label' => false,
       'query' => ProfileTable::buildQueryForLiveSearch()
    )));
    
    $this->getWidgetSchema()->setFormFormatterName('List');
  }
}
