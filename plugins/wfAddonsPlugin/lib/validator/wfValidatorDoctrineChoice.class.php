
<?php
/**
 * This class extends sfValidatorDoctrineChoice and adding the following option:
 * * return_pks - doClean will return the object's primaryKey(s) rather than the value(s) passed to the validator
 * * return_object - doClean will return the object(s) rather than the value(s) passed to the validator
 * * store_object - keep a reference to the object so it can be accessed later
 * * create_object_callback - Call this method to create an object if one isn't found. This method can return false to not create an object.
 * * * Not implemented for multiple choice.
 * It also stores the retrieved objects, so they can be fetched later by making a call on the validator
 */

class wfValidatorDoctrineChoice extends sfValidatorDoctrineChoice
{
  protected $object;

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addOption('return_pk', false);
    $this->addOption('return_object', false);
    $this->addOption('store_object', false);
    $this->addOption('create_object_callback', false);
  }

   /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if ($query = $this->getOption('query'))
    {
      $query = clone $query;
    }
    else
    {
      $query = Doctrine_Core::getTable($this->getOption('model'))->createQuery();
    }

    if ($this->getOption('multiple'))
    {
      if (!is_array($value))
      {
        $value = array($value);
      }

      if (isset($value[0]) && !$value[0])
      {
        unset($value[0]);
      }

      $count = count($value);

      if ($this->hasOption('min') && $count < $this->getOption('min'))
      {
        throw new sfValidatorError($this, 'min', array('count' => $count, 'min' => $this->getOption('min')));
      }

      if ($this->hasOption('max') && $count > $this->getOption('max'))
      {
        throw new sfValidatorError($this, 'max', array('count' => $count, 'max' => $this->getOption('max')));
      }

      $query->andWhereIn(sprintf('%s.%s', $query->getRootAlias(), $this->getColumn()), $value);

      if ($this->getOption('return_pk') || $this->getOption('return_object') || $this->getOption('store_object'))
      {
        $objects = $query->execute();
        if ($objects->count() != count($value))
        {
          throw new sfValidatorError($this, 'invalid', array('value' => $value));
        }
        if ($this->getOption('store_object'))
        {
          $this->object = $objects;
        }
        if ($this->getOption('return_object'))
        {
          return $objects;
        }
        if ($this->getOption('return_pk'))
        {
          return $objects->getPrimaryKeys();
        }
      }
      if ($query->count() != count($value))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }
    else
    {
      $query->andWhere(sprintf('%s.%s = ?', $query->getRootAlias(), $this->getColumn()), $value);

      if ($this->getOption('return_pk') || $this->getOption('return_object') || $this->getOption('store_object'))
      {
        $object = $query->fetchOne();
        if (!$object)
        {
          if ($this->getOption('create_object_callback'))
          {
            $object = call_user_func($this->getOption('create_object_callback'), $value);
          }
          if (!$object)
          {
            throw new sfValidatorError($this, 'invalid', array('value' => $value));
          }
        }
        if ($this->getOption('store_object'))
        {
          $this->object = $object;
        }
        if ($this->getOption('return_object'))
        {
          return $object;
        }
        if ($this->getOption('return_pk'))
        {
          return $object->getPrimaryKey();
        }
      }
      else if (!$query->count())
      {
        $object = false;
        if ($this->getOption('create_object_callback'))
        {
          $object = call_user_func($this->getOption('create_object_callback'), $value);
        }
        if (!$object)
        {
          throw new sfValidatorError($this, 'invalid', array('value' => $value));
        }
      }
    }

    return $value;
  }

  /**
   * The object that was found by the validator when cleaning. Only stored if option store_object is true.
   * @return Doctrine_Record
   */
  public function getObject()
  {
    return $this->object;
  }
}