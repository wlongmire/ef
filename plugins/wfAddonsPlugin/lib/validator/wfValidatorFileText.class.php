<?php
class wfValidatorFileText extends sfValidatorFile
{
  /**
   * Adds the following options to sfValidatorFile:
   * * return_text: true to return the text of the file, rather than an instance of validated_file_class
   * @param array $options
   * @param array $messages
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('return_text', false);
  }

  /**
   * This validator always returns a sfValidatedFile object. Overridden to support "return_text" option.
   *
   * The input value must be an array with the following keys:
   *
   *  * tmp_name: The absolute temporary path to the file
   *  * name:     The original file name (optional)
   *  * type:     The file content type (optional)
   *  * error:    The error code (optional)
   *  * size:     The file size in bytes (optional)
   *
   * @see sfValidatorBase
   * @return sfValidatedFile|string
   */
  protected function doClean($value)
  {
    $file = parent::doClean($value);
    if ($this->getOption('return_text'))
    {
      return file_get_contents($file->getTempName());
    }
    return $file;
  }
}
