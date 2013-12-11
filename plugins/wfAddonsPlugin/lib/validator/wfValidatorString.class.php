<?php
/**
 * wfValidatorString adds an option pattern and nohtml option to sfValidatorString
 *
 * @author     Jeremy Kauffman <kauffj@gmail.com>
 * @see        sfValidatorString
 */
class wfValidatorString extends sfValidatorString
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * pattern: A regex pattern compatible with PCRE (required)
   *  * nohtml: strip_html_entities
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorString
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('pattern');
    $this->addOption('nohtml');
    $this->addOption('exclude');
    
    $this->setOption('empty_value', null);    
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    if ($this->hasOption('pattern') && !preg_match($this->getOption('pattern'), $clean))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    
    if ($this->hasOption('exclude') && 
      in_array($clean, is_array($this->getOption('exclude')) ? $this->getOption('exclude') : array($this->getOption('exclude'))))
    {
    	throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }
    
    if ($this->getOption('nohtml'))
    {
      $clean = htmlspecialchars($clean);
    }

    return $clean;
  }
}