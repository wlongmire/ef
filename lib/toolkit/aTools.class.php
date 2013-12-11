<?php

class aTools extends BaseaTools
{  
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
  
  static public function getAssetVersion()
  {
    $path = aFiles::getUploadFolder(array('asset-cache')) . '/asset-version';
    $filemtime = file_exists($path) ? filemtime($path) : false;
    if ($filemtime === false) //it doesn't exist
    {
      touch($path);
      $filemtime = filemtime($path);
    }
    return $filemtime;
  }
  
    /**
   * Get template choices in the new format, then provide bc with the old format
   * (one level with no engines specified), and also add entries for any engines
   * listed in the old way that don't have templates specified in the new way
   * @return mixed
   */
  static public function getTemplates()
  {
    if (sfConfig::get('app_a_get_templates_method'))
    {
      $method = sfConfig::get('app_a_get_templates_method');

      return call_user_func($method);
    }
    $templates = sfConfig::get('app_a_templates', array(
      'a' => array(
        'default' => 'Default Page',
        'home' => 'Home Page')));
    // Provide bc 
    $newTemplates = $templates;
    foreach ($templates as $key => $value)
    {
      if (!is_array($value))
      {
        $newTemplates['a'][$key] = $value;
        unset($newTemplates[$key]);
      }
    }
    $templates = $newTemplates;
    $engines = aTools::getEngines();
    foreach ($engines as $name => $label)
    {
      if (!strlen($name))
      {
        // Ignore the "template-based" engine option
        continue;
      }
      if (!isset($templates[$name]))
      {
        $templates[$name] = array('default' => $label);
      }
    }
    return $templates;
  }

}