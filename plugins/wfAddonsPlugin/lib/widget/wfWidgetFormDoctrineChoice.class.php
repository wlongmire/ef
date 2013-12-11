<?php
class wfWidgetFormDoctrineChoice extends sfWidgetFormDoctrineChoice
{
  /**
   * Constructor.
   *
   * Adds the following options:
   *
   *  * add_empty_to_end: (default: false) Add the "add_empty" option to the end of the choices instead of the beginning
   *  * name_callback: (default: null) If provided, callback function will be called on each object for names. This will override the
   *  * * "method" option.
   *
   * @see sfWidgetFormSelect
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('add_empty_to_end', false);
    $this->addOption('name_callback', false);
  }

  /**
   * Returns the choices associated to the model.
   *
   * @return array An array of choices
   */
  public function getChoices()
  {
    if (!isset($this->choices))
    {
      $this->choices = array();
      $objects = $this->getChoiceObjects();
      if (false !== $this->getOption('add_empty') && $this->getOption('add_empty_to_end') !== true)
      {
        $this->choices[''] = true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty'));
      }

      $callback = $this->getOption('name_callback');
      $method = $this->getOption('method');
      $keyMethod = $this->getOption('key_method');

      foreach ($objects as $object)
      {
        $this->choices[$object->$keyMethod()] = $callback ? call_user_func($callback, $object) : $object->$method();
      }

      if (false !== $this->getOption('add_empty') && $this->getOption('add_empty_to_end') === true)
      {
        $this->choices[''] = true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty'));
      }
    }

    return $this->choices;
  }

  /**
   * @return array|Doctrine_Collection  The results of the query to find the choices
   */
  public function getChoiceObjects()
  {
    if (!isset($this->choiceObjects))
    {
      if (null === $this->getOption('table_method'))
      {
        $query = null === $this->getOption('query') ? Doctrine_Core::getTable($this->getOption('model'))->createQuery() : $this->getOption('query');
        if ($order = $this->getOption('order_by'))
        {
          $query->addOrderBy($order[0] . ' ' . $order[1]);
        }
        $objects = $query->execute();
      }
      else
      {
        $tableMethod = $this->getOption('table_method');
        $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod();

        if ($results instanceof Doctrine_Query)
        {
          $objects = $results->execute();
        }
        else if ($results instanceof Doctrine_Collection)
        {
          $objects = $results;
        }
        else if ($results instanceof Doctrine_Record)
        {
          $objects = new Doctrine_Collection($this->getOption('model'));
          $objects[] = $results;
        }
        else
        {
          $objects = array();
        }
      }

      $this->choiceObjects = $objects;
    }

    return $this->choiceObjects;
  }
}
