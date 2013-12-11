<?php

/**
 * Event form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EventForm extends BaseEventForm
{
  public function configure()
  {
    unset($this['updated_at'], $this['created_at']);
    
    $this->setWidget('blurb', new aWidgetFormRichTextarea(array(
        'label' => 'Event Description'
    )));
    $this->setValidator('blurb', new sfValidatorHtml(array(
        'required' => false, 
    )));
    
    $this->setWidget('media_item_id', new myWidgetFormMediaImageSimple(array(
        'object' => $this->object,
        'label' => 'Photo'
    )));
    $this->setValidator('media_item_id', new myValidatorFileMediaImageSimple(array(
        'required' => false
    )));    
    
    if ($this->isNew())
    {
      $this->setWidget('media_item_copy_id', new sfWidgetFormInputHidden()); //used only for copying
      $this->setValidator('media_item_copy_id', new sfValidatorPass()); //used only for copying
      $this->setDefault('media_item_copy_id', $this->object->media_item_id); //if we're making a copy, we'll have an id already
    }
    $this->getWidgetSchema()->setHelp('media_item_id', 'One image, square looks best, under 8mb, jpg, png or gif format.');
      
    $this->getWidget('min_cost')->setAttribute('size', 6);
    $this->getWidget('max_cost')->setAttribute('size', 6);
    $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validateCostRange')
    )));

    $this->setWidget('venue_id', new wfWidgetFormJqueryDoctrineAutocompleter(array(
      'model' => 'Venue',
      'value_callback' => array($this, 'getVisibleValueForVenueId')
    ), array(
      'class' => 'lock silent-fail'
    )));
    $this->getWidgetSchema()->setHelp('venue_id', 'Select a matching venue or add a new venue.');
    
    if ($this->getOption('is_admin') && $this->object->exists())
    {
      $this->getWidgetSchema()->setHelp('venue_id', $this->object->suggested_venue_name ? 
                                                      'Submitter suggested "' . $this->object->suggested_venue_name . '"' :
                                                      'Submitter did not provide a venue.');
    }
    
    $this->setWidget('suggested_venue_name', new sfWidgetFormInputHidden()); //if a user types a name into venue that is not a match, it will get here
    
    $site = Site::current();
    
    $tagHeadings = $this->getOption('guard_user') && $this->getOption('guard_user')->is_super_admin ?
                      TagHeadingTable::getInstance()->findAll() :
                      $site->TagHeadings;
    if ($tagHeadings)
    {
      if (count($tagHeadings) == 1)
      {
        $tagChoices = array();
        foreach($tagHeadings->getFirst()->Tag as $tag)
        {
          $tagChoices[$tag->id] = $tag->name;
        }
        $this->setWidget('tags', new sfWidgetFormSelectCheckbox(array(
            'choices' => $tagChoices
        )));
        
        $this->setValidator('tags', new sfValidatorChoice(array(
            'choices' => array_keys($tagChoices),
            'multiple' => true,
            'required' => false
        )));                       
      }
      else
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
    else 
    {
      unset($this['tags']);
    }
        
    $this->getWidgetSchema()->setLabel('url', 'Website');
    $this->getWidgetSchema()->setLabel('name', 'Event Name');
    
    $disciplineTree = DisciplineTable::findEnabledSortedTreesForSite($site);
    $eventTypes = EventTypeTable::findForSite($site);

    $this->setWidget('profiles_list', new wfWidgetFormJqueryDoctrineSelectMany(array(
       'model' => 'Profile',
       'current_header' => 'Current Profiles',
       'autocomplete_header' => 'Search Profiles',
       'label' => 'Profiles',
       'query' => ProfileTable::buildQueryForLiveSearch()
    )));
      
    if ($site->mode == Site::MODE_ENTRY && $site->name != 'eventsfilter')
    {
      unset($this['owners_list']);
    }
    else
    {      
      $this->getWidgetSchema()->moveField('owners_list', sfWidgetFormSchema::LAST);      
      $this->setWidget('owners_list', new wfWidgetFormJqueryDoctrineSelectMany(array(
         'model' => 'sfGuardUser',
         'current_header' => 'Accounts',
         'autocomplete_header' => 'Search For Accounts',
         'label' => 'Who can manage?',
         'query' => sfGuardUserTable::buildQueryForLiveSearch()
      )));    
    }
    
    $this->setWidget('disciplines_list', new myWidgetFormFilterTree(array(
      'tree' => $disciplineTree,
      'title' => 'Disciplines',
      'label' => 'Disciplines',
      'multiple' => true
    )));   
    
    $this->setWidget('event_type_id', new myWidgetFormEventType(array(
      'choices' => $eventTypes,
      'label' => 'Type of Event'
    )));  
    
    $this->configureEventRecurrance();
    $this->configureEventOccurances();
  }
  
  protected function doBind(array $values)
  {
    $maxOccurances = $this->getValidator('event_occurance_count')->clean($values['event_occurance_count']);
    $dateMode = isset($this->validatorSchema['date_mode']) && isset($values['date_mode']) ? $values['date_mode'] : 'manual';
    
    if ($dateMode == 'manual')
    {
      $this->skipSavingForm('event_recurrance_object');
      $count = 0;
      foreach($this->eventOccuranceIndices as $index)
      {
        if ($count >= $maxOccurances)
        {
          $key = 'event_occurance_' . $index;        
          $this->validatorSchema['event_occurance_objects'][$key] = new sfValidatorPass();
          $this->getEmbeddedForm('event_occurance_objects')->skipSavingForm($key);
        }
        $count++;
      }
    }
    else
    {
      $this->skipSavingForm('event_occurance_objects');
    }
    
    parent::doBind($values);
    
    if ($this->hasErrors() && $this->taintedValues['suggested_venue_name'] && !$this->taintedValues['event_id'])
    {
      $this->taintedValues['event_id'] = $this->taintedValues['suggested_venue_name'];
    }
  }
  
  public function validateCostRange(sfValidatorBase $base, $values)
  {
    if (isset($values['max_cost']) && $values['max_cost'] !== '') //then min_cost got everything
    {
      if ($values['max_cost'] && !isset($values['min_cost']) || $values['min_cost'] === '')
      {
        $values['min_cost'] = $values['max_cost'];
        unset($values['max_cost']);
      }
      elseif ($values['min_cost'] > $values['max_cost'])
      {
        $currentMin = $values['min_cost'];
        $values['min_cost'] = $values['max_cost'];
        $values['max_cost'] = $currentMin;
      }
    }
    return $values;
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
  
  /**
   * Process event occurances
   * @param array $values
   * @return EventProduct
   */
  public function updateObject($values = null)
  {
    if (null === $values)
    {
      $values = $this->values;
    }

    $values = $this->processValues($values);
    $dateMode = isset($values['date_mode']) ? $values['date_mode'] : null;
    $maxOccurances = $values['event_occurance_count'];
    unset($values['event_occurance_count'], $values['date_mode']);

    if (isset($this->item))
    {
      $this->item->preSaveFile($this->file);
      $this->item->save();
      $this->item->saveFile($this->file);       
      $values['media_item_id'] = $this->item->id;
    }
    else
    {
      if ($this->isNew() && $values['media_item_copy_id'])
      {
        $values['media_item_id'] = $values['media_item_copy_id'];
        unset($values['media_item_id_copy']);
      }
      else
      {
        $values['media_item_id'] = $this->object->media_item_id;
      }
    }
    
    if ($values['venue_id'] || $this->getOption('is_admin')) //admins can never suggest a name
    {
      unset($values['suggested_venue_name']);
    }
    
    $this->doUpdateObject($values);    
    if ($dateMode == 'recur')
    {
      $this->updateEventOccurancesRecurring($values);
    }
    else
    {
      $this->updateEventOccurancesManual($values, $maxOccurances); //$values is reference!
    }
    
    if (!$this->getOption('is_admin'))
    {
      if ($this->isNew())
      {
        $this->object->Owners->add($this->getOption('guard_user'));            
      }
    }  

    // embedded forms
    $this->updateObjectEmbeddedForms($values);

    $fixedTags = Site::current()->getFixedTagNames();
    if ($fixedTags)
    {
      $this->object->addTag($fixedTags);
    }
    
    return $this->getObject();
  }
  
  protected function configureEventRecurrance()
  {
    $choices = array(
        'manual' => 'Manually',
        'recur' => 'As a recurring event'
    );
    $this->setWidget('date_mode', new sfWidgetFormSelectRadio(array(
        'choices' => $choices,
        'default' => 'manual',
        'label' => 'How do you want to enter dates?'
    )));
    $this->setValidator('date_mode', new sfValidatorChoice(array(
        'choices' => array_keys($choices)
    )));

    $eventReccuranceForm = new EventRecurranceForm($this->object->EventRecurrance);
    $this->embedForm('event_recurrance_object', $eventReccuranceForm);
    $this->getWidgetSchema()->setLabel('event_recurrance_object', '&nbsp;');
  }
  
  protected function configureEventOccurances()
  {
    $maxOccurances = 25;
    $choices = range(1, $maxOccurances);
    $choices = array_combine($choices, array_map(function($num) { return $num . ' date' . ($num != 1 ? 's' : ''); }, $choices));
    $this->setWidget('event_occurance_count', new sfWidgetFormSelect(array(
        'choices' => $choices,
        'label' => 'Event Occurances'
    )));
    $this->setValidator('event_occurance_count', new sfValidatorChoice(array(
        'choices' => array_keys($choices)
    )));

    $index = -1; //will get overwritten in for loop if indexes exist
    $count = 0;
    $form = new BaseForm();
    
    foreach($this->object->EventOccurances as $index => $eventOccurance)
    {
      $this->embedEventOccuranceForm($eventOccurance, $form, $index);
      ++$count;
    }

    for($count; $count < $maxOccurances; $count++)
    {
      $eventOccurance = new EventOccurance();
      $this->embedEventOccuranceForm($eventOccurance, $form, ++$index);
    }
    
    $this->embedForm('event_occurance_objects', $form);
    $this->getWidgetSchema()->setLabel('event_occurance_objects', '&nbsp;');
  }

  protected function embedEventOccuranceForm(EventOccurance $eo, sfForm $parent, $index)
  {
    $key = 'event_occurance_' . $index;
    $eventOccuranceForm = new EventOccuranceForm($eo);
    if (!isset($this->maxSeenDate))
    {
      $this->maxSeenDate = new DateTime();      
    }
    if (!$eventOccuranceForm->getDefault('start_date'))
    {
      $eventOccuranceForm->setDefault('start_date', $this->maxSeenDate->format('m/d/Y'));
      $this->maxSeenDate->modify('+1 day');
    }
    elseif ($eventOccuranceForm->getDefault('start_date') > $this->maxSeenDate->format('Y-m-d'))
    {
      $this->maxSeenDate->setTimestamp(strtotime($eventOccuranceForm->getDefault('start_date')));
      $this->maxSeenDate->modify('+1 day');
    }
    $parent->embedForm($key, $eventOccuranceForm);
    $parent->getWidgetSchema()->setLabel($key, false);
    $this->eventOccuranceIndices[] = $index;
  }
  
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    $this->setDefault('event_occurance_count', $this->object->EventOccurances->count());
    if (isset($this['tags']))
    {
      $this->setDefault('tags', $this->object->getTags());      
    }
    if ($this->object->EventRecurrance->exists())
    {
      $this->setDefault('date_mode', 'recur');
    }
    elseif ($this->object->EventOccurances->count() > 0)
    {
      $this->setDefault('date_mode', 'manual');
    }
  }
  
  public function getVisibleValueForVenueId($id)
  {
    if (!is_numeric($id))
    {
      return $id;
    }
    $venue = Doctrine_Core::getTable('Venue')->findOneBy('id', $id);
    if ($venue)
    {
      return $venue->name;
    }
    return '';
  }
  
  protected function updateEventOccurancesRecurring($values)
  {    
    $recurValues = $values['event_recurrance_object'];
    $days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
    $existingDates = array();

    $startDate = new DateTime($recurValues['start_date']);
    $endDate = new DateTime($recurValues['end_date']);
    $startDateDb = $startDate->format('Y-m-d');
    $endDateDb = $startDate->format('Y-m-d');
    foreach($this->object->EventOccurances as $index => $eo)
    {
      $dayOfWeek = strtolower(date('l', strtotime($eo->start_date)));
      if ($eo->start_date != $eo->end_date || !$recurValues[$dayOfWeek] || $eo->start_date < $startDateDb || $eo->end_date > $endDate ||
            $recurValues['start_time'] != $eo->start_time || $recurValues['end_time'] != $eo->end_time)
      {
        $this->object->EventOccurances->remove($index);
      }
      else
      {
        $existingDates[$eo->start_date] = $eo->start_date;
      }
    }

    for($startDate; $startDate < $endDate; $startDate->modify('+1 day'))
    {
      $date = $startDate->format('Y-m-d');
      if (!isset($existingDates[$date]) && $recurValues[strtolower($startDate->format('l'))])
      {
        $eventOccurance = new EventOccurance();
        $eventOccurance->start_date = $date;
        $eventOccurance->end_date = $date;
        $eventOccurance->start_time = $recurValues['start_time'];
        $eventOccurance->end_time = $recurValues['end_time'];
        $this->object->EventOccurances->add($eventOccurance);
      }
    }
  }
  
  protected function updateEventOccurancesManual(&$values, $maxOccurances)
  {    
    $parentForm = $this->getEmbeddedForm('event_occurance_objects');    
    $this->object->refreshRelated('EventType');
    $isDaily = $this->object->EventType->is_daily;
    if ($isDaily)
    {
      $maxOccurances = 1;
    }

    $count = 0;
    $this->object->unlink('EventRecurrance');
    foreach($this->eventOccuranceIndices as $index)
    {
      $key = 'event_occurance_' . $index;
      if ($count >= $maxOccurances)
      {
        if ($this->object->EventOccurances->contains($index))
        {
          $this->object->EventOccurances->get($index)->event_id = null;
          $this->object->EventOccurances->remove($index);
        }
        unset($this->embeddedForms[$key]);
        unset($values['event_occurance_objects'][$key]);
      }    
      else
      {
        $eventOccurance = $parentForm->getEmbeddedForm($key)->getObject();
        $eventOccurance->Event = $this->object;
        $this->object->EventOccurances->add($eventOccurance);
        $count++;
        if ($isDaily)
        {
          unset($values['event_occurance_objects'][$key]['start_time']);
          unset($values['event_occurance_objects'][$key]['end_time']);
        }
        else
        {
          unset($values['event_occurance_objects'][$key]['end_date']);
        }
      }
    }
  }
  
  public function getJavascripts()
  {
    return array_merge(array('form/event'), parent::getJavascripts());
  }
}
