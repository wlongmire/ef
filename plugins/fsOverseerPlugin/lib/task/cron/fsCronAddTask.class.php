<?php
class fsCronAddTask extends fsCronBaseTask
{
  protected function configure()
  {
    parent::configure();
    $this->name                 = 'add';
    $this->briefDescription     = 'Add task to cron';

    $this->addArgument('name', sfCommandArgument::REQUIRED, 'The task name');
    $this->addArgument('command', sfCommandArgument::REQUIRED, 'The command to run');
    $this->addArgument('frequency', sfCommandArgument::OPTIONAL, 'How often to run the command (as a DateInterval string). Skip to run command once and not repeat.');

    $this->addOption('priority', null, sfCommandOption::PARAMETER_REQUIRED, 'What order to run this command in relative to other commands');
    $this->addOption('disabled', null, sfCommandOption::PARAMETER_NONE, 'Should the task start disabled');
    $this->addOption('next-run', null, sfCommandOption::PARAMETER_REQUIRED, 'When the task should be run next (as a PHP strtotime() string)');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->initState();

    $name = $arguments['name'];
    $taskWithThisName = wfToolkit::arrayFind($this->tasks->toArray(), function($task) use ($name) {
      return $name == $task['name'];
    });

    if ($taskWithThisName)
    {
      $this->logError('Task with this name already exists.');
      return 1;
    }

    $task = fsCronTask::create($name, $arguments['command'], $arguments['frequency']);
    $task->enabled = !$options['disabled'];
    $task->next_run = date('Y-m-d H:i:s', strtotime($options['next-run']));

    if ($options['priority'])
    {
      $task->priority = $options['priority'];
    }

    $task->save();
    
    $this->logSection($this->getName(), 'Added ' . $task['name']);
  }
}