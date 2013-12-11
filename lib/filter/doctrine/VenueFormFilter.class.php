<?php

/**
 * Venue filter form.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class VenueFormFilter extends BaseVenueFormFilter
{
  public function configure()
  {
    parent::configure();
    
    $this->setWidget('location_id', new myWidgetFormFilterTree(array(
        'tree' => LocationTable::getInstance()->getTree()->findSortedTrees()
    )));    
  }
}
