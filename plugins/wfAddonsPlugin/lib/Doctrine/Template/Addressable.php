<?php

/**
 * Doctrine_Template_Imageable
 *
 * Adds a
 */
class Doctrine_Template_Addressable extends Doctrine_Template
{
  /**
   * Array of Timestampable options
   *
   * @var string
   */
  protected $_options = array('required' => true);

  /**
   * __construct
   *
   * @param string $array
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  /**
   * Set table definition for Timestampable behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $required = $this->_options['required'];
    $this->hasColumn('address_1', 'string', 100, array('notnull' => $required));
    $this->hasColumn('address_2', 'string', 100, array('notnull' => false)); //address 2 never required
    $this->hasColumn('city', 'string', 100, array('notnull' => $required));
    $this->hasColumn('state', 'string', 2, array('notnull' => $required, 'regexp' => '/[A-Za-z]{2}/'));
    $this->hasColumn('zip_code', 'string', 6, array('notnull' => $required, 'regexp' => '/[A-Za-z0-9]{5,6}/'));
  }

  public function getAddressFields()
  {
    return array('address_1', 'address_2', 'city', 'state', 'zip_code');
  }

  /**
   * @param array $options Options for how to return the address. Valid options:
   * * single_line: return the address as a single line (default: false)
   * * multi_line_separator: the separator to use if the address is multi line (default: '<br/>')
   * * single_line_separator: the separator to use if the address is single line (default: ', ')
   * @return string
   */
  public function getAddress($options = array())
  {
    $options = array_merge(array(
        'single_line' => false,
        'single_line_separator' => ', ',
        'multi_line_separator' => '<br/>'
    ), $options);

    $invoker = $this->getInvoker();
    $parts = array();
    if ($invoker->address_1)
    {
      $parts[] = $invoker->address_1;
    }
    if ($invoker->address_2)
    {
      $parts[] = $invoker->address_2;
    }
    $cityStateZip = '';
    if ($invoker->city)
    {
      $cityStateZip = $invoker->city;
    }
    if ($invoker->state)
    {
      $cityStateZip .= ($cityStateZip ? ', ' : '') . $invoker->state;
    }
    if ($invoker->zip_code)
    {
      $cityStateZip .= ($cityStateZip ? ' ' : '') . $invoker->zip_code;
    }
    $parts[] = $cityStateZip;
    $glue = $options['single_line'] ? $options['single_line_separator'] : $options['multi_line_separator'];
    return implode($glue, $parts);
  }
}
