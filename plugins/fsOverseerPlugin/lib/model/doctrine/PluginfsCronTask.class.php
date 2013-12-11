<?php

abstract class PluginfsCronTask extends BasefsCronTask
{
  protected $neverRun = false;

  public function shouldRun(DateTime $now)
  {
    return !$this->neverRun &&
           $this['enabled'] &&
           $now->format('Y-m-d H:i:s') >= $this->next_run;
  }

  public function justRan(DateTime $runTime)
  {
    if (!$this->frequency)
    {
      $this->neverRun = true;
      $this->delete();
    }
    else
    {
      $nextRun = clone $runTime;
      $interval = DateInterval::createFromDateString($this->frequency);
      $nextRun->add($interval);
      $this->next_run = $nextRun->format('Y-m-d H:i:s');
      $this->save();
    }
  }

  public static function create($name, $command, $frequency = null)
  {
    $task = new fsCronTask();
    $task->name = $name;
    $task->command = $command;
    if ($frequency)
    {
      $task->setFrequency($frequency);
    }

    return $task;
  }

  public function setFrequency($frequency)
  {
    $this->validateFrequency($frequency);
    $this->_set('frequency', $frequency);
  }

  public function validateFrequency($frequency, $throwError = true)
  {
    $interval = DateInterval::createFromDateString($frequency);
    $now = new DateTime();
    $dateTest = clone $now;
    $dateTest->add($interval);
    if ($now->format('c') >= $dateTest->format('c'))
    {
      if ($throwError)
      {
        throw new InvalidArgumentException($frequency . 'is not a valid positive DateInterval format.');
      }
      return false;
    }
    return true;
  }
}
