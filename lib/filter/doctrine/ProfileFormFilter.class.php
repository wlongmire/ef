<?php

/**
 * Profile filter form.
 *
 * @package    eventsfilter
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProfileFormFilter extends BaseProfileFormFilter
{
  public function configure()
  {
    $this->setWidget('location_id', new myWidgetFormFilterTree(array(
        'tree' => LocationTable::getInstance()->getTree()->findSortedTrees()
    )));   
  }
}
