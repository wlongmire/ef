<?php
/**
 * Functions we want added to aTools for all Apostrophe projects
 *
 * @author jeremy
 */
class wfATools extends BaseaTools
{
  /**
   * Sets the title on $response to $title with title_prefix and title_suffix
   * @param sfWebResponse $response
   * @param string $title
   */
  static public function setTitle(sfWebResponse $response, $title)
  {
    $prefix = aTools::getOptionI18n('title_prefix');
    $suffix = aTools::getOptionI18n('title_suffix');
    $response->setTitle($prefix . $title . $suffix);
  }

  /**
   * @param string $slotType
   * @param string $variant
   * @return array The variant options for a $slotType slot for variant $variant
   */
  static public function getSlotVariantOptions($slotType, $variant)
  {
    $variants = sfConfig::get('app_a_slot_variants');
    $slotVariants = isset($variants[$slotType]) ? $variants[$slotType] : array();
    $slotVariant = isset($slotVariants[$variant]) ? $slotVariants[$variant] : array();
    return isset($slotVariant['options']) ? $slotVariant['options'] : array();
  }
}
