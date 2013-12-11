<?php

abstract class PluginfsCronTaskTable extends Doctrine_Table
{
  protected static function baseQuery()
  {
    return Doctrine_Query::create()
      ->from('fsCronTask fct INDEXBY fct.id')
      ->select('fct.*');
  }

  public static function getTasks($orderBy = null)
  {
    $q = static::baseQuery();

    if ($orderBy)
    {
      $q->orderBy($orderBy);
    }

    return $q->execute();
  }

  public static function existsByName($name)
  {
    return static::baseQuery()
      ->where('fct.name = ?', $name)
      ->count() > 0;
  }

  public static function removeAllTasks()
  {
    return Doctrine_Query::create()
      ->delete('fsCronTask fct INDEXBY fct.id')
      ->execute();
  }
}