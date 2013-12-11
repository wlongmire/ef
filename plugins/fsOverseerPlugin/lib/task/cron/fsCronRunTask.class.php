<?php
class fsCronRunTask extends fsCronBaseTask
{
  protected function configure()
  {
    parent::configure();
    $this->name                 = 'run';
    $this->briefDescription     = 'Run all tasks that need to be run';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->requireExclusiveLock();

    $this->initState('priority ASC');

    $now = new DateTime();
    foreach($this->tasks as $task)
    {
      if ($task->shouldRun($now))
      {
        $this->stdout = $this->stderr = '';
        try
        {
          $this->getFilesystem()->execute($task['command'], array($this,'stdoutCallback'), array($this,'stderrCallback'));
        }
        catch (RuntimeException $e)
        {
          $this->sendErrorEmail($task, $e, $now);
        }

        $task->justRan($now);
      }
    }

    $this->freeExclusiveLock();
  }

  

  public function stdoutCallback($output)
  {
    $this->stdout .= $output;
  }

  public function stderrCallback($error)
  {
    $this->stderr .= $error;
  }


  protected function sendErrorEmail($task, $error, $now)
  {
    $this->getMailer()
      ->sendNextImmediately()
      ->composeAndSendHtml(sfConfig::get('app_mail_default_user'), 'lyoshenka@gmail.com', 'Task Error - ' . $task['name'],
        strtr(<<<EOF
<p><strong>%name% - %command%</strong></p>

<pre style="font-size: 1.25em">

Run at: %now%

Exit code: %errorCode%


<strong>Output</strong>
%stdOut%

<strong>Error</strong>
%stdErr%
</pre>

EOF
        , array(
          '%name%' => $task['name'],
          '%command%' => $task['command'],
          '%now%' => $now->format('c'),
          '%errorCode%' => $error->getCode(),
          '%stdOut%' => $this->stdOut,
          '%stdErr%' => $this->stdErr,
        )));
  }
}