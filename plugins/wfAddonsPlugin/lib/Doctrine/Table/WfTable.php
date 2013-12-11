<?php

class Wf_Doctrine_Table extends Doctrine_Table
{
  /**
   * @param Doctrine_Collection|array $collection
   * @param string $field The field to index the records by
   * @param string $secondaryIndex. Optional. A second field to index the 2nd level array by
   * @return array A 2-dimentional array indexed by $field.
   */
  public static function reIndex($collection, $field, $secondaryIndex = false)
  {
    $indexed = array();
    foreach($collection as $item)
    {
      $key = $item[$field];
      if ($secondaryIndex)
      {
        $indexed[$key][$item[$secondaryIndex]] = $item;
      }
      else
      {
        $indexed[$key][] = $item;
      }
    }
    return $indexed;
  }

  /**
   * @param string $column
   * @return integer The length of the column $column
   */
  public function getColumnLength($column)
  {
    $definition = $this->getColumnDefinition($column);
    return (integer)$definition['length'];
  }

  /**
   * @param string $column
   * @return integer|float|null The maximum value of the column according to it's range definition, NULL if no range is set
   */
  public function getColumnMax($column)
  {
    $definition = $this->getColumnDefinition($column);
    if (isset($definition['range']))
    {
      list($min, $max) = $definition['range'];
      return $max;
    }
    return null;
  }


    /**
   * @param string $column
   * @return array|null An array with the min and max DateTimes. Array has min and max as keys with min first. If no range exists,
   * null will be returned. If column is not a date type, null is returned.
   */
  public function getColumnDateRange($column)
  {
    $definition = $this->getColumnDefinition($column);
    if (isset($definition['daterange']))
    {
      list($min, $max) = $definition['daterange'];
      return array('min' => isset($min) ? new DateTime($min) : null, 'max' => isset($max) ? new DateTime($max) : null);
    }
    return null;
  }

  /**
   * @param Doctrine_Record|array|integer $object
   * @param string $field Defaults to getPrimaryKey in the case of a Doctrine_Record, to "id" in the case of an array
   * @return integer|string The $field value of $object, or $object itself of it is neither an array nor a Record
   */
  public function getObjectField($object, $field = null)
  {
    if ($object instanceof Doctrine_Record)
    {
      return $field === null ? $object->getPrimaryKey() : $object->$field;
    }
    else if (is_array($object))
    {
      return $field === null ? $object['id'] : $object[$field];
    }
    return $object;
  }
  
  public function buildQueryForSubfilter($active = null, array $profileIds = array(), array $eventIds = array())
  {
    $query = $this->getTree()->getBaseQuery();
    if ($active && $active instanceof Doctrine_Record)
    {
      $query->addWhere(sprintf('root_id = %s AND lft > %s AND rgt < %s AND level > %s', 
                          $active->root_id, $active->lft, $active->rgt, $active->level));
    }
    if ($profileIds && method_exists($this, 'buildQueryForProfileIds'))
    {
      $this->buildQueryForProfileIds($query, $profileIds);
      $query->groupBy($query->getRootAlias() . '.id');
    }
    if ($eventIds && method_exists($this, 'buildQueryForEventIds'))
    {
      $this->buildQueryForEventIds($query, $eventIds);
      $query->groupBy($query->getRootAlias() . '.id');
    }    
    return $query;
  }

  /**
   * @param string $proposal The base proposal
   * @param Doctrine_Record $record The object the proposal is for
   * @return string The slug for the object
   */
  public static function buildSlug($proposal, $record)
  {
    $proposal = Doctrine_Inflector::unaccent($proposal);

    $proposal = strtr($proposal, '-', ' ');

    // Remove all non word and space characters
    $proposal = preg_replace('/[^\w\s]/', '', $proposal);

    $proposal = preg_replace('/  +/', ' ', $proposal); // squash consequtive spaces

    return strtolower(trim($proposal, '- '));
  }
}