<?php
class fsCronRemoveTask extends fsCronBaseTask
{
  protected function configure()
  {
    parent::configure();
    $this->name                 = 'remove';
    $this->briefDescription     = 'Remove a task from cron';

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

    $name = $this->tasks[$arguments['id']]['name'];
    $this->tasks[$arguments['id']]->delete();
    $this->logSection($this->getName(), $name . ' removed');
  }
}