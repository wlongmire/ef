<?php

/**
 * sfGuardUser form base class.
 *
 * @method sfGuardUser getObject() Returns the current form's model object
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'first_name'             => new sfWidgetFormInputText(),
      'last_name'              => new sfWidgetFormInputText(),
      'email_address'          => new sfWidgetFormInputText(),
      'username'               => new sfWidgetFormInputText(),
      'algorithm'              => new sfWidgetFormInputText(),
      'salt'                   => new sfWidgetFormInputText(),
      'password'               => new sfWidgetFormInputText(),
      'is_active'              => new sfWidgetFormInputCheckbox(),
      'is_super_admin'         => new sfWidgetFormInputCheckbox(),
      'last_login'             => new sfWidgetFormDateTime(),
      'full_name'              => new sfWidgetFormTextarea(),
      'profile_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Profile'), 'add_empty' => true)),
      'created_at'             => new sfWidgetFormDateTime(),
      'updated_at'             => new sfWidgetFormDateTime(),
      'groups_list'            => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup')),
      'permissions_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission')),
      'categories_list'        => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory')),
      'blog_editor_items_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem')),
      'owned_profiles_list'    => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Profile')),
      'owned_events_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Event')),
      'owned_venues_list'      => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Venue')),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'first_name'             => new sfValidatorString(array('max_length' => 255)),
      'last_name'              => new sfValidatorString(array('max_length' => 255)),
      'email_address'          => new sfValidatorEmail(array('max_length' => 255)),
      'username'               => new sfValidatorString(array('max_length' => 128)),
      'algorithm'              => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'salt'                   => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'password'               => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'is_active'              => new sfValidatorBoolean(array('required' => false)),
      'is_super_admin'         => new sfValidatorBoolean(array('required' => false)),
      'last_login'             => new sfValidatorDateTime(array('required' => false)),
      'full_name'              => new sfValidatorString(array('max_length' => 510)),
      'profile_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Profile'), 'required' => false)),
      'created_at'             => new sfValidatorDateTime(),
      'updated_at'             => new sfValidatorDateTime(),
      'groups_list'            => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardGroup', 'required' => false)),
      'permissions_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardPermission', 'required' => false)),
      'categories_list'        => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aCategory', 'required' => false)),
      'blog_editor_items_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'aBlogItem', 'required' => false)),
      'owned_profiles_list'    => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Profile', 'required' => false)),
      'owned_events_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Event', 'required' => false)),
      'owned_venues_list'      => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Venue', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('email_address'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('username'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUser', 'column' => array('profile_id'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_guard_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUser';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['groups_list']))
    {
      $this->setDefault('groups_list', $this->object->Groups->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['permissions_list']))
    {
      $this->setDefault('permissions_list', $this->object->Permissions->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['categories_list']))
    {
      $this->setDefault('categories_list', $this->object->Categories->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['blog_editor_items_list']))
    {
      $this->setDefault('blog_editor_items_list', $this->object->BlogEditorItems->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['owned_profiles_list']))
    {
      $this->setDefault('owned_profiles_list', $this->object->OwnedProfiles->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['owned_events_list']))
    {
      $this->setDefault('owned_events_list', $this->object->OwnedEvents->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['owned_venues_list']))
    {
      $this->setDefault('owned_venues_list', $this->object->OwnedVenues->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveGroupsList($con);
    $this->savePermissionsList($con);
    $this->saveCategoriesList($con);
    $this->saveBlogEditorItemsList($con);
    $this->saveOwnedProfilesList($con);
    $this->saveOwnedEventsList($con);
    $this->saveOwnedVenuesList($con);

    parent::doSave($con);
  }

  public function saveGroupsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['groups_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Groups->getPrimaryKeys();
    $values = $this->getValue('groups_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Groups', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Groups', array_values($link));
    }
  }

  public function savePermissionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['permissions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Permissions->getPrimaryKeys();
    $values = $this->getValue('permissions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Permissions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Permissions', array_values($link));
    }
  }

  public function saveCategoriesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['categories_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Categories->getPrimaryKeys();
    $values = $this->getValue('categories_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Categories', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Categories', array_values($link));
    }
  }

  public function saveBlogEditorItemsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['blog_editor_items_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->BlogEditorItems->getPrimaryKeys();
    $values = $this->getValue('blog_editor_items_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('BlogEditorItems', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('BlogEditorItems', array_values($link));
    }
  }

  public function saveOwnedProfilesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['owned_profiles_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->OwnedProfiles->getPrimaryKeys();
    $values = $this->getValue('owned_profiles_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('OwnedProfiles', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('OwnedProfiles', array_values($link));
    }
  }

  public function saveOwnedEventsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['owned_events_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->OwnedEvents->getPrimaryKeys();
    $values = $this->getValue('owned_events_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('OwnedEvents', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('OwnedEvents', array_values($link));
    }
  }

  public function saveOwnedVenuesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['owned_venues_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->OwnedVenues->getPrimaryKeys();
    $values = $this->getValue('owned_venues_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('OwnedVenues', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('OwnedVenues', array_values($link));
    }
  }

}
