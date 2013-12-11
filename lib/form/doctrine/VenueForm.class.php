<?php

/**
 * Venue form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class 
VenueForm extends BaseVenueForm
{
  public function configure()
  {
    unset($this['updated_at'], $this['created_at'], $this['media_item_id'], $this['venue_types_list']);    
    
    $this->setWidget('blurb', new aWidgetFormRichTextarea());
    $this->setValidator('blurb', new sfValidatorHtml(array(
        'required' => false
    )));
    
    $this->getWidgetSchema()->setLabel('url', 'Website');
    
    $this->setWidget('state', new sfWidgetFormSelectUsstate());
    $this->setDefault('state', 'PA');
    
    $this->getWidget('zip_code')
      ->setAttribute('size', 5)
      ->setAttribute('maxlength', 5);
    $this->getValidator('zip_code')
      ->setOption('max_length', 5)
      ->setOption('pattern', '/[0-9]{5}/');
    
    $this->setWidget('owners_list', new wfWidgetFormJqueryDoctrineSelectMany(array(
       'model' => 'sfGuardUser',
       'current_header' => 'Accounts',
       'autocomplete_header' => 'Search For Accounts',
       'label' => 'Who can manage?',
       'query' => sfGuardUserTable::buildQueryForLiveSearch()
    )));    
    
    $this->setWidget('location_id', new myWidgetFormFilterTree(array(
        'tree' => LocationTable::getInstance()->getTree()->findSortedTrees()
    )));    
  }
}
