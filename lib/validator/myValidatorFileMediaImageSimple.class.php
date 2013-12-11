<?php

/**
 * Description of myValidatorFileMediaImageSimple
 *
 * @author jeremy
 */
class myValidatorFileMediaImageSimple extends aValidatorFilePersistent
{
  public function configure($options = array(), $messages = array())
  {
    $options['mime_types'] = array('gif' => "image/gif", "png" => "image/png", "jpg" => "image/jpeg");
    $messages['mime_types'] = 'The following file types are accepted: ' . implode(', ', array_keys($options['mime_types']));
    parent::configure($options, $messages);
    $this->setOption('validated_file_class', 'aValidatedFile');
  }
  
  public function clean($dirty)
  {
    if ($this->isEmpty($dirty))
    {
      return $this->getEmptyValue();
    }
    return parent::clean(array(
       'newfile' => array('tmp_name' => $dirty['tmp_name'], 'name' => $dirty['name']),
       'persistid' => aGuid::generate()
    ));
  }
}
