<?php
class fsCronDisableTask extends fsCronBaseTask
{
  protected function configure()
  {
    parent::configure();
    $this->name                 = 'disable';
    $this->briefDescription     = 'Disable a task';

    $this->addArgument('id', sfCommandArgument::REQUIRED, 'The id of the task');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->initState();

    if (!isset($this->tasks[$arguments['id']]))
    {
      $this->logSection($this->getName(), 'No task with id ' . $arguments['id']);
      return;
    }

    $this->tasks[$arguments['id']]['enabled'] = false;
    $this->tasks->save();
    $this->logSection($this->getName(), $arguments['id'] . ' disabled');
  }
}