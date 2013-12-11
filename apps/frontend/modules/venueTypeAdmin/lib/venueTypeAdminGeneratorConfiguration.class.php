<?php

/**
 * venueTypeAdmin module configuration.
 *
 * @package    eventsfilter
 * @subpackage venueTypeAdmin
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class venueTypeAdminGeneratorConfiguration extends BasevenueTypeAdminGeneratorConfiguration
{
  public function getTableMethod()
  {
    return 'createQuery';
  }
}
