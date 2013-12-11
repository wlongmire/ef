<?php
abstract class wfDoctrineRecord extends sfDoctrineRecord
{
  /**
   * Overridden _get to:
   * * handle user timezones
   * @see lib/vendor/doctrine/Doctrine/Doctrine_Record#_get($fieldName, $load)
   * @see http://kriswallsmith.net/post/136226720/doctrine-timestamps-and-user-timezones
   */	
  protected function _get($fieldName, $load = true)
  {
    if ($value = parent::_get($fieldName, $load))
    {
      $column = $this->getTable()->getColumnDefinition($fieldName);
      if ($column && 'timestamp' == $column['type'])
      {
        $timezone = date_default_timezone_get();
        if (sfConfig::get('sf_default_timezone') != $timezone)
        {
          // shift value to the current timezone
          date_default_timezone_set(sfConfig::get('sf_default_timezone'));
          $time = strtotime($value);

          date_default_timezone_set($timezone);
          $value = date('Y-m-d H:i:s', $time);
        }
      }
    }

    return $value;
  }

  /**
   * Overridden _set to handle user timezones
   * @see lib/vendor/doctrine/Doctrine/Doctrine_Record#_set($fieldName, $value, $load)
   * @see http://kriswallsmith.net/post/136226720/doctrine-timestamps-and-user-timezones
   */   
  protected function _set($fieldName, $value, $load = true)
  {
    $column = $this->getTable()->getColumnDefinition($fieldName);
    if ($column && 'timestamp' == $column['type'] && $time = strtotime($value))
    {
      $timezone = date_default_timezone_get();
      if (sfConfig::get('sf_default_timezone') != $timezone)
      {
        // shift value to the default timezone
        date_default_timezone_set(sfConfig::get('sf_default_timezone'));
        $value = date('Y-m-d H:i:s', $time);

        date_default_timezone_set($timezone);
      }
    }

    return parent::_set($fieldName, $value, $load);
  }
}
