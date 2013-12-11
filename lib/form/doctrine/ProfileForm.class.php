<?php

/**
 * Profile form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProfileForm extends BaseProfileForm
{
  protected $urlServices = array('personal', 'facebook', 'twitter', 'youtube', 'vimeo');
  
  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['events_list'], $this['id']);
    
    $site = Site::current();
    
    if (isset($this['location_id']) && !$this->getWidget('location_id') instanceof myWidgetFormFilterTree)
    {
      $this->setWidget('location_id', new myWidgetFormFilterTree(array(
          'tree' => LocationTable::getInstance()->getTree()->findSortedTrees('name')
      )));      
      $this->getValidator('location_id')->setOption('required', true);      
    }
    
    if (isset($this['categories_list']))
    {
      $this->setWidget('categories_list', new myWidgetFormFilterTree(array(
        'tree' => CategoryTable::getInstance()->getTree()->findSortedTrees(),
      )));   
    }
    
    if (isset($this['disciplines_list']))
    {
      $this->setWidget('disciplines_list', new myWidgetFormFilterTree(array(
        'tree' => DisciplineTable::getInstance()->findEnabledSortedTreesForSite($site),
        'multiple' => true
      )));          
    }
    
    if (isset($this['blurb']))
    {
      $this->setWidget('blurb', new aWidgetFormRichTextarea());
      $this->setValidator('blurb', new sfValidatorHtml(array(
          'required' => false
      )));      
    }
    
    if (isset($this['url']))
    {
      $this->getWidget('url')->setLabel('Website');
    }
    
    $this->getWidgetSchema()->setHelp('profile_url', 'Currently only your personal website is displayed.');
    
    if ($this->getOption('with_tags'))
    {
      $tagHeadings = Site::current()->TagHeadings;
      if (count($tagHeadings))
      {
        $this->setWidget('tags', new myWidgetFormJQueryTaggable(array(
           'tag_headings' => $tagHeadings
        )));
        $this->setValidator('tags', new sfValidatorChoice(array(
            'choices' => TagHeadingTable::collectTagNames($tagHeadings),
            'multiple' => true,
            'required' => false
        )));      
      }
    }
  }
  
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    if (isset($this['tags']))
    {
      $this->setDefault('tags', $this->object->getTags());      
    }
    if ($this->getOption('has_websites'))
    {
      foreach($this->object->Urls as $url)
      {
        $this->setDefault('url_' . $url['type'], $url['url']);
      }
    }
  }
  
  protected function doUpdateObject($values)
  {
    if ($this->getOption('has_websites'))
    {
      $services = array_combine($this->urlServices, $this->urlServices);
      $unlink = array();
      foreach($this->object->Urls as $profileUrl)
      {
        $field = 'url_' . $profileUrl['type'];
        if (isset($values[$field]) && $values[$field])
        {
          $profileUrl['url'] = $values[$field];
          unset($services[$profileUrl['type']]);
        }
        else
        {
          $unlink[] = $profileUrl['id'];
        }
        unset($values[$field]);
      }
      if ($unlink)
      {
        $this->object->unlink('Urls', $unlink);
      }
      foreach($services as $service)
      {
        $field = 'url_' . $service;
        if (isset($values[$field]) && $values[$field])
        {
          $profileUrl = new ProfileUrl();
          $profileUrl->type = $service;
          $profileUrl->url = $values[$field];
          $this->object->Urls->add($profileUrl);
        }        
        unset($values[$field]);
      }
    }
    
    parent::doUpdateObject($values);        
  }
  
  protected function configureWebsites()
  {
    $this->setOption('has_websites', true);
    foreach($this->urlServices as $service)
    {
      $field = 'url_' . $service;
      $this->setWidget($field, new sfWidgetFormInputText(array('label' => ucwords($service) . ' URL'), array('maxlength' => 255, 'size' => 40)));
      $this->setValidator($field, new wfValidatorUrl(array('required' => false)));
    }
  }
}
