<?php
class fsCronListTask extends fsCronBaseTask
{
  protected function configure()
  {
    parent::configure();
    $this->name                 = 'list';
    $this->briefDescription     = 'List tasks';

    $this->addOption('order-by', null, sfCommandOption::PARAMETER_REQUIRED, 'What column to order the listed tasks by');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->initState($options['order-by']);

    if (!count($this->tasks))
    {
      $this->logSection($this->getName(), 'No tasks');
      return;
    }

    $columns = $this->getColumnsAndMaxLengths();
    unset($columns['created_at'], $columns['updated_at']); // we don't want to print these

    $header = implode(' | ', array_map(function($key, $value) {
      return str_pad($key, $value);
    }, array_keys($columns), $columns));

    $hr = str_pad('', strlen($header), '-');

    echo '--' . $hr . "--\n";
    echo '| ' . $header . " |\n";
    echo '|-' . $hr . "-|\n";

    foreach($this->tasks->toArray() as $task)
    {
      $task = array_intersect_key($task, $columns); // only print columns that are set
      echo '| ' . implode(' | ', array_map(function($value, $length) {
        return str_pad($value, $length);
      }, $task, $columns)) . " |\n";
    }

    echo '--' . $hr . "--\n";
  }

  /**
   * Get the lengths of each column so they can be lined up nicely for printing
   * @return array an array of key-value pairs where the key is the column title and the value is either the length of
   *               the longest string in that column or the length of the column title, whichever is longer
   */
  protected function getColumnsAndMaxLengths()
  {
    return array_reduce(

      # replace each value with the length of that value
      array_map(function($task) {
        return array_map(function($value) {
          return strlen($value);
        }, $task);
      }, $this->tasks->toArray()),

      # find the max length of each value (or the length of the column name) across all tasks
      function($a, $b) {
        if (!$a) $a = $b;
        $ret = array();
        foreach ($a as $key => $value)
        {
          $ret[$key] = max($a[$key], $b[$key], strlen($key));
        }
        return $ret;
      });
  }
}