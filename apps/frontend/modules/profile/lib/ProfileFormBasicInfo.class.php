<?php

/**
 * Description of ProfileUserForm
 *
 * @author jeremy
 */
class ProfileFormBasicInfo extends ProfileForm 
{
  public function configure()
  {
    parent::configure();
      
    $this->useFields(array('is_group', 'name', 'display_email', 'media_item_id', 'blurb', 'home_zip_code', 'studio_zip_code', 'location_id'));
    $this->configureWebsites();
    
    
    $this->setWidget('is_group', new sfWidgetFormSelectRadio(array(
        'choices' => array(
            0 => 'An individual',
            1 => 'An organization or group'
        ),
        'label' => 'This profile is for...'
    )));      
    
    $this->getValidator('name')->setOption('required', true)->setOption('trim', true);
    $this->getWidgetSchema()->setLabels(array_merge($this->getWidgetSchema()->getLabels(), array(
        'blurb' => 'A paragraph or two about you',
        'name' => 'Listing name',
        'location_id' => 'Location'
    )));
    $this->getWidgetSchema()->setFormFormatterName('AAdmin');
    $this->getWidgetSchema()->setHelps(array(
        'name' => 'You may have a separate listing name than your account (real) name.',
        'display_email' => 'Your email will be hidden from spam bots.',
        'studio_zip_code' => 'Zip codes are not shown in your profile. They are for "arts mapping" purposes only.',
        'media_item_id' => 'Only <strong>one</strong> images is displayed in your Profile. Square images look best.',        
        'location_id' => 'The more specific your location, the more results you will appear in.'
    ));    
    
    $this->setWidget('media_item_id', new myWidgetFormMediaImageSimple(array(
        'object' => $this->object,
        'label' => 'Photo'
    )));
    
    $this->setValidator('media_item_id', new myValidatorFileMediaImageSimple(array(
        'required' => false
    )));
  }
  
  protected function processUploadedFile($field, $filename = null, $values = null)
  {
    if ($field == 'media_item_id' && $values[$field] instanceof sfValidatedFile)
    {
      $this->file = $values[$field];
      $this->item = new aMediaItem();
      $this->item->title = ($this->getObject()->getUser()->getFullName() ?: 'Unknown') . ' Profile Picture';
    }
  }
  
  protected function doUpdateObject($values)
  {
    $isGroupOrig = $this->object->is_group;
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
    parent::doUpdateObject($values);
    
    if ($isGroupOrig != $this->object->is_group)
    {
      $this->object->unlink('Categories');
    }
  }
}
