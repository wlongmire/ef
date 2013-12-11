<?php

/**
 * event module helper.
 *
 * @package    eventsfilter
 * @subpackage event
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventAdminGeneratorHelper extends sfModelGeneratorHelper
{
public function linkToNew($params)
  {
    return '<li class="a-admin-action-new">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'apostrophe'), $this->getUrlForAction('new'), array() ,array("class"=>"a-btn icon big a-add")).'</li>';
  }

  public function linkToEdit($object, $params)
  {
    return '<li class="a-admin-action-edit">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'apostrophe'), $this->getUrlForAction('edit'), $object, array('class'=>'a-btn icon no-label a-edit')).'</li>';
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return '<li class="a-admin-action-delete">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'apostrophe'), $this->getUrlForAction('delete'), $object, array('class'=>'a-btn icon no-label a-delete alt','method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'apostrophe') : $params['confirm'])).'</li>';
  }

  public function linkToList($params)
  {
    return '<li class="a-admin-action-list">'.link_to('<span class="icon"></span>'.__('Cancel', array(), 'apostrophe'), $this->getUrlForAction('list'), array(), array('class'=>'a-btn icon a-cancel')).'</li>';
  }

  public function linkToSave($object, $params)
  {
    return '<li class="a-admin-action-save">' . a_submit_button(a_('Save', array(), 'apostrophe'), array('a-save'), '_save') . '</li>';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if (!$object->isNew())
    {
      return '';
    }
    return '<li class="a-admin-action-save-and-add">' . a_anchor_submit_button(a_($params['label']), array('a-save'), '_save_and_add') . '</li>';
  }

  public function linkToSaveAndList($object, $params)
  {
    return '<li class="a-admin-action-save-and-list">' . a_anchor_submit_button(a_('Save'), array('a-save'), '_save_and_list') . '</li>';
  }
  

  public function getUrlForAction($action)
  {
    return 'list' == $action ? 'event_admin' : 'event_admin_'.$action;
  }
  
  public function getTreeClass($record)
  {
    $node = $record->getNode();
    if ($node->hasParent())
    {
      return " child-of-node-" . $node->getParent()->getId();
    }
    return '';
  }  
}
