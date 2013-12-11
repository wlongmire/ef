<?php
class fsCronRemoveAllTask extends fsCronBaseTask
{
  protected function configure()
  {
    parent::configure();
    $this->name                 = 'remove-all';
    $this->briefDescription     = 'Remove all tasks';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->initState();

    if ($this->askConfirmation('Are you sure you want to remove all the tasks?', 'QUESTION', false))
    {
      $this->tasks->delete();
      $this->logSection($this->getName(), 'All tasks removed');
    }
  }
}