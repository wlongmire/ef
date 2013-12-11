<?php

/**
 * VenueType form.
 *
 * @package    eventsfilter
 * @subpackage form
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class VenueTypeForm extends BaseVenueTypeForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at']);
  }
}
