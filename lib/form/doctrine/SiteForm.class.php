<?php

/**
 * Site form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SiteForm extends BaseSiteForm
{
  public function configure()
  {
    unset($this['id'], $this['created_at'], $this['updated_at']);
    $themes = array('eventsfilter', 'phlocal', 'pifva', 'theartblog');
    $this->setWidget('theme', new sfWidgetFormSelect(array(
        'choices' => array_combine($themes, $themes)
    )));
    
    $this->setWidget('domain', new sfWidgetFormInputText());
    
    $this->getWidgetSchema()->moveField('domain', sfWidgetFormSchema::AFTER, 'name');
            
    if ($this->object->exists())
    {
      $this->getWidget('name')->setAttribute('readonly', 'readonly');
    }
    
    $this->originalContent = $this->getDefault('less_file');
    
    $this->getWidgetSchema()->setHelps(array(
        'name' => 'Unique name for the site. Site will be accessable at &lt;name&gt;.phlocal.com as well as whatever domain you add.',
        'domain' => 'Domain name for the site. TOP LEVEL only (e.g. designphiladelphia.org, not events.designphiladelphia.org.',
        'org_abbr' => 'e.g. PIFVA (not used yet)',
        'org_name' => 'e.g. Philadelphia Independent Film & Video Association',
        'tag_headings_list' => 'For entry sites, only tags with these groups will be shown.',
        'location_id' => 'For listing sites, this serves as the fallback location. Also restricts filter options shown on pages inside listing site.',
        'disciplines_list' => 'For entry sites, only disciplines within these categories will be shown.',
        'tags' => 'For entry sites, these tags will be applied to all events. For listings, only events with this tag will be shown. NOT RETROACTIVE AT THE MOMENT.'
    ));
    
    $this->setWidget('logo_id', new myWidgetFormMediaImageSimple(array(
        'object' => $this->object,
        'relation' => 'Logo',
        'label' => 'Logo'
    )));
    $this->setValidator('logo_id', new myValidatorFileMediaImageSimple(array(
        'required' => false
    )));  
    
    if ($this->object->exists())
    {
      $this->getValidatorSchema()->setPostValidator(new sfValidatorPass()); //hack hack hack
    }
    
    $this->setWidget('location_id', new myWidgetFormFilterTree(array(
        'tree' => LocationTable::getInstance()->getTree()->findSortedTrees('name')
    )));
    
    $tagHeadings = TagHeadingTable::getInstance()->findAll();
    $this->setWidget('tags', new myWidgetFormJQueryTaggable(array(
       'tag_headings' => $tagHeadings,
       'label' => 'Fixed Tags'
    )));
    $this->setValidator('tags', new sfValidatorChoice(array(
        'choices' => TagHeadingTable::collectTagNames($tagHeadings),
        'multiple' => true,
        'required' => false
    )));       
    $this->setDefault('tags', $this->object->getTags());
    
    $this->setWidget('disciplines_list', new myWidgetFormFilterTree(array(
      'tree' => DisciplineTable::getInstance()->getTree()->findSortedTrees(),
      'title' => 'Disciplines',
      'label' => 'Disciplines',
      'multiple' => true
    )));   
    
    $this->setWidget('less_file', new wfWidgetFormJqueryTextarea(array(), array(
        'style' => 'width: 700px !important'
    )));
    $this->setValidator('less_file', new sfValidatorPass());
    
    if ($this->isNew())
    {
      $this->setDefault('less_file', file_get_contents(sfConfig::get('sf_web_dir') . '/css/less/phlocal.less'));
    }
    else
    {
      $this->setDefault('less_file', file_get_contents($this->object->getGlobalLessPath()));
    }
  }
  
  protected function processUploadedFile($field, $filename = null, $values = null)
  {
    if ($field == 'logo_id' && $values[$field] instanceof sfValidatedFile)
    {
      $this->file = $values[$field];
      $this->item = new aMediaItem();
      $this->item->title = ($this->object->org_name ?: $values['org_name']) . ' Site Logo';
    }
  }
  
  protected function doUpdateObject($values)
  {
    if (isset($values['less_file']))
    {
      $lessFile = $values['less_file'];
      unset($values['less_file']);
    }
    
    if (isset($this->item))
    {
      $this->item->preSaveFile($this->file);
      $this->item->save();
      $this->item->saveFile($this->file);       
      $values['logo_id'] = $this->item->id;
    }
    else
    {
      $values['logo_id'] = $this->object->logo_id;
    }
    
    $this->object->updated_at = date('Y-m-d H:i:s');
    
    parent::doUpdateObject($values);
    
    if ($lessFile != $this->originalContent)
    {
      file_put_contents($this->object->getGlobalLessPath(), $lessFile);
    }
  }
}
