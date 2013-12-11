<?php

require_once dirname(__FILE__).'/../lib/tagAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/tagAdminGeneratorHelper.class.php';

/**
 * aTagAdmin actions.
 * @package    apostrophePlugin
 * @subpackage aTagAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class tagAdminActions extends autoTagAdminActions
{

    /**
   * DOCUMENT ME
   * @return mixed
   */
  protected function buildQuery()
  {
    $query = Doctrine::getTable('Tag')->createQuery('r');
    Doctrine::getTable('Tag')->queryTagsWithCountsByModel(array('Profile', 'Event'), $query);
    $this->addSortQuery($query);
    return $query;
  }
}
