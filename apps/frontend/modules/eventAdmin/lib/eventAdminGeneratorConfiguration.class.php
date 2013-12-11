<?php

/**
 * event module configuration.
 *
 * @package    eventsfilter
 * @subpackage event
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventAdminGeneratorConfiguration extends sfModelGeneratorConfiguration
{
  public function getFilterDefaults()
  {
//    return array();
    return array('include_past_events' => 'no');
  }
  
  public function getCredentials($action)
  {
    if (0 === strpos($action, '_'))
    {
      $action = substr($action, 1);
    }

    return isset($this->configuration['credentials'][$action]) ? $this->configuration['credentials'][$action] : array();
  }

  public function getActionsDefault()
  {
    return array(  '_new' =>   array(    'label' => 'New Event',  ),);
  }

  public function getFormActions()
  {
    return array(  '_save_and_list' => NULL,  '_list' => NULL,  '_delete' => NULL,);
  }

  public function getNewActions()
  {
    return array(  '_save' =>   array(    'label' => 'Create Event',  ),  '_list' =>   array(    'label' => 'Cancel',  ),);
  }

  public function getEditActions()
  {
    return array();
  }

  public function getListObjectActions()
  {
    return array(  '_edit' => NULL,  'copy' =>   array(    'label' => '<span class="icon"></span>Copy',    'action' => 'copy',    'params' =>     array(      'class' => 'a-btn icon a-page-normal',    ),  ),  '_delete' => NULL,);
  }

  public function getListActions()
  {
    return array(  '_new' => NULL,);
  }

  public function getListBatchActions()
  {
    return array(  '_delete' => NULL,);
  }
  public function getListParams()
  {
    return '%%=name%% - %%_venue%% - %%_profiles%% - %%created_at%%';
  }

  public function getListLayout()
  {
    return 'tabular';
  }

  public function getListTitle()
  {
    return 'Event Admin';
  }

  public function getEditTitle()
  {
    return 'Edit Event';
  }

  public function getNewTitle()
  {
    return 'New Event';
  }

  public function getFilterDisplay()
  {
    return array();
  }

  public function getFormDisplay()
  {
    return array();
  }

  public function getEditDisplay()
  {
    return array();
  }

  public function getNewDisplay()
  {
    return array();
  }

  public function getListDisplay()
  {
    return array(  0 => '=name',  1 => '_venue',  2 => '_profiles',  3 => 'created_at',);
  }

  public function getFieldsDefault()
  {
    return array(
      'id' => array(  'is_link' => true,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'venue_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'event_type_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'media_item_id' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'ForeignKey',),
      'name' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'blurb' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'min_cost' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'max_cost' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'url' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'ticket_url' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'is_published' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Boolean',),
      'suggested_venue_name' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'created_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'updated_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'profiles_list' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'disciplines_list' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'owners_list' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'id' => array(),
      'venue_id' => array(),
      'event_type_id' => array(),
      'media_item_id' => array(),
      'name' => array(),
      'blurb' => array(),
      'min_cost' => array(),
      'max_cost' => array(),
      'url' => array(),
      'ticket_url' => array(),
      'is_published' => array(),
      'suggested_venue_name' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'profiles_list' => array(),
      'disciplines_list' => array(),
      'owners_list' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'id' => array(),
      'venue_id' => array(),
      'event_type_id' => array(),
      'media_item_id' => array(),
      'name' => array(),
      'blurb' => array(),
      'min_cost' => array(),
      'max_cost' => array(),
      'url' => array(),
      'ticket_url' => array(),
      'is_published' => array(),
      'suggested_venue_name' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'profiles_list' => array(),
      'disciplines_list' => array(),
      'owners_list' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'id' => array(),
      'venue_id' => array(),
      'event_type_id' => array(),
      'media_item_id' => array(),
      'name' => array(),
      'blurb' => array(),
      'min_cost' => array(),
      'max_cost' => array(),
      'url' => array(),
      'ticket_url' => array(),
      'is_published' => array(),
      'suggested_venue_name' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'profiles_list' => array(),
      'disciplines_list' => array(),
      'owners_list' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'id' => array(),
      'venue_id' => array(),
      'event_type_id' => array(),
      'media_item_id' => array(),
      'name' => array(),
      'blurb' => array(),
      'min_cost' => array(),
      'max_cost' => array(),
      'url' => array(),
      'ticket_url' => array(),
      'is_published' => array(),
      'suggested_venue_name' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'profiles_list' => array(),
      'disciplines_list' => array(),
      'owners_list' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'id' => array(),
      'venue_id' => array(),
      'event_type_id' => array(),
      'media_item_id' => array(),
      'name' => array(),
      'blurb' => array(),
      'min_cost' => array(),
      'max_cost' => array(),
      'url' => array(),
      'ticket_url' => array(),
      'is_published' => array(),
      'suggested_venue_name' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'profiles_list' => array(),
      'disciplines_list' => array(),
      'owners_list' => array(),
    );
  }


  public function getForm($object = null, $options = array())
  {
    $class = $this->getFormClass();

    $user = sfContext::getInstance()->getUser();
    $options['is_admin'] = $user->hasCredential('admin');
    $options['guard_user'] = $user->getGuardUser();
    $form = new $class($object, array_merge($this->getFormOptions(), $options));
    
    if (isset($form['owners_list']) && !$options['is_admin'])
    {
      unset($form['owners_list']);
    }
    
    if (!$user->hasCredential(array('admin', 'site_admin'), false))
    {
      unset($form['ticket_url']);
      unset($form['profiles_list']);
    }
    
    if (!$user->hasCredential(array('admin', 'site_admin', 'venue_manage'), false))
    {
      unset($form['is_published']);
    }
    elseif ($form->isNew())
    {
      $form->setDefault('is_published', true);
    }
    
    return $form;
  }

  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return 'EventForm';
  }

  public function getFormOptions()
  {
    return array();
  }

  public function hasFilterForm()
  {
    return true;
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getFilterFormClass()
  {
    return 'EventFormFilter';
  }

  public function getFilterForm($filters)
  {
    $class = $this->getFilterFormClass();

    return new $class($filters, $this->getFilterFormOptions());
  }

  public function getFilterFormOptions()
  {
    return array('query' => EventTable::getInstance()->createQuery('ev'));
  }

  public function getPager($model)
  {
    $class = $this->getPagerClass();

    return new $class($model, $this->getPagerMaxPerPage());
  }

  public function getPagerClass()
  {
    return 'sfDoctrinePager';
  }

  public function getPagerMaxPerPage()
  {
    return 20;
  }

  public function getDefaultSort()
  {
    return array('created_at', 'desc');
  }

  public function getTableCountMethod()
  {
    return '';
  }

  public function getConnection()
  {
    return null;
  }  
}
