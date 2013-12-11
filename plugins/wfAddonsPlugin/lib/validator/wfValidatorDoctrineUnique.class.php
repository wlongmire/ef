<?php
/**
 * Description of wfValidatorDoctrineUnique
 *
 * @author jeremy
 */
class wfValidatorDoctrineUnique extends sfValidatorDoctrineUnique
{
  /**
   * Adds the following additional options:
   *
   *  * object: The existing model object. If provided, it will use the object for column values that aren't passed to doClean.
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addOption('object', null);
  }

 /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    $originalValues = $values;
    $originalObject = $this->getOption('object');
    $table = Doctrine_Core::getTable($this->getOption('model'));
    if (!is_array($this->getOption('column')))
    {
      $this->setOption('column', array($this->getOption('column')));
    }

    //if $values isn't an array, make it one
    if (!is_array($values))
    {
      //use first column for key
      $columns = $this->getOption('column');
      $values = array($columns[0] => $values);
    }

    $q = Doctrine_Core::getTable($this->getOption('model'))->createQuery('a');

    $hasColumnValue = false; //make sure at least one value in 'column' was provided
    foreach ($this->getOption('column') as $column)
    {
      $colName = $table->getColumnName($column);
      if (!array_key_exists($column, $values))
      {
        if (!$originalObject || !isset($originalObject->$colName))
        {
          // one of the column has be removed from the form
          return $originalValues;
        }
        else
        {
          $values[$colName] = $originalObject->$colName;
        }
      }
      else
      {
        $hasColumnValue = true;
      }

      $q->addWhere('a.' . $colName . ' = ?', $values[$column]);
    }

    if (!$hasColumnValue)
    {
      return $originalValues;
    }

    $newObject = $q->fetchOne();

    // if no object or if we're updating the object, it's ok
    if (!$newObject || ($originalObject && $originalObject->getPrimaryKey() == $newObject->getPrimaryKey()) || $this->isUpdate($newObject, $values))
    {
      return $originalValues;
    }

    $error = new sfValidatorError($this, 'invalid', array('column' => implode(', ', $this->getOption('column'))));

    if (!$this->getOption('throw_global_error')) //if we're not set to throw a global error, throw on the first column found in values
    {
      foreach($this->getOption('column') as $column)
      {
        if (isset($originalValues[$column]))
        {
          throw new sfValidatorErrorSchema($this, array($column => $error));
        }
      }
    }

    throw $error;
  }
}