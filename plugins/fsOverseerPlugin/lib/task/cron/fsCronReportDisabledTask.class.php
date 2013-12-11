<?php
class fsCronReportDisabledTask extends fsCronBaseTask
{
  protected function configure()
  {
    parent::configure();
    $this->name                 = 'report-disabled';
    $this->briefDescription     = 'Send email listing the disabled tasks';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->initState('name ASC');

    $disabled = array();
    foreach($this->tasks as $task)
    {
      if (!$task['enabled'])
      {
        $disabled[] = $task['name'];
      }
    }

    if ($disabled)
    {
      $this->getMailer()
        ->sendNextImmediately()
        ->composeAndSend(sfConfig::get('app_mail_default_user'), 'lyoshenka@gmail.com', 'Disabled Tasks ' . date('Y-m-d H:i:s'), implode("\n", $disabled));
    }
  }
}