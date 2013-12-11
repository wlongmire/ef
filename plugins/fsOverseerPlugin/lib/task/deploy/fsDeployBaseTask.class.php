<?php
abstract class fsDeployBaseTask extends fsBaseTask
{
  protected function configure()
  {
    $this->namespace = 'fs';
    $this->name = 'deploy';
    $this->briefDescription = 'Deploy project to remote server.';

    $this->remotePreDeployTask = 'fs:pre-deploy';
    $this->remotePostDeployTask = 'fs:post-deploy';

    $this->addArgument('server', sfCommandArgument::REQUIRED, 'The name of the server from properties.ini');

    $this->addOption('just-deploy', null, sfCommandOption::PARAMETER_NONE, 'Skip all pre and post steps');
    $this->addOption('dry-run', null, sfCommandOption::PARAMETER_NONE, 'Skip all pre and post steps and runs rsync in dry-run mode.');

    $this->addOption('skip-local-pre', null, sfCommandOption::PARAMETER_NONE, 'Skip running the local preDeploy steps');
    $this->addOption('skip-local-post', null, sfCommandOption::PARAMETER_NONE, 'Skip running the local postDeploy steps');

    $this->addOption('skip-remote-pre', null, sfCommandOption::PARAMETER_NONE, 'Skip running the remote pre-deploy task');
    $this->addOption('skip-remote-post', null, sfCommandOption::PARAMETER_NONE, 'Skip running the remote post-deploy task');
  }

  protected function execute($arguments = array(), $options = array())
  {
    if ($options['dry-run'])
    {
      $this->logSection($this->getName(), 'DRY RUN - Skipping all pre and post tasks.');
    }

    if ($options['just-deploy'] || $options['dry-run'])
    {
      $options['skip-local-pre'] = true;
      $options['skip-remote-pre'] = true;
      $options['skip-local-post'] = true;
      $options['skip-remote-post'] = true;
    }

    if ($options['skip-local-pre'] && !$options['skip-remote-pre'])
    {
      $this->runRemoteTask($this->remotePreDeployTask, $arguments['server']);
    }
    else if (!$options['skip-local-pre'])
    {
      foreach($this->getPreDeploySteps($arguments, $options) as $func => $params)
      {
        call_user_func_array(array($this, $func), $params);
      }
    }


    $this->deploy($arguments['server'], $options['dry-run']);


    if ($options['skip-local-post'] && !$options['skip-remote-post'])
    {
      $this->runRemoteTask($this->remotePostDeployTask, $arguments['server']);
    }
    else if (!$options['skip-local-post'])
    {
      foreach($this->getPostDeploySteps($arguments, $options) as $func => $params)
      {
        call_user_func_array(array($this, $func), $params);
      }
    }
  }

  
  /**
   * Returns an array of methods to call. Overwrite this to change what happens before deploy() is called
   * @param array $arguments The arguments for this task
   * @param array $options The options for this task
   * @return array An array with of method names and arguments to pass those methods
   */
  protected function getPreDeploySteps($arguments = array(), $options = array())
  {
    return array(
      'fixPermissions' => array(),
      'runRemoteTask' => array($options['skip-remote-pre'] ? null : $this->remotePreDeployTask, $arguments['server'])
    );
  }


  /**
   * Returns an array of methods to call. Overwrite this to change what happens after deploy() is called
   * @param array $arguments The arguments for this task
   * @param array $options The options for this task
   * @return array An array with of method names and arguments to pass those methods
   */
  protected function getPostDeploySteps($arguments = array(), $options = array())
  {
    return array(
      'runRemoteTask' => array($options['skip-remote-post'] ? null : $this->remotePostDeployTask, $arguments['server'])
    );
  }


  /**
   * Deploys project to the given server
   * @param string $server The name of the server from the properties.ini file
   */
  protected function deploy($server, $dryRun = false)
  {
    $this->runTask('project:deploy', array(
      'server' => $server
    ), array_merge(array(
      'rsync-options' => '"-azvCcI --no-t --force --delete --progress"',
      'trace',
      ), ($dryRun ? array() : array('go'))
    ));
  }

  
  /**
   * Runs a task on the remote server
   * @param string $server The name of the server from the properties.ini file
   * @param string $task The name of the task to run
   */
  protected function runRemoteTask($task, $server)
  {
    if ($task)
    {
      $this->runTask('fs:ssh', array(
        'server' => $server,
        'command' => $task
      ), array(
        'task'
      ));
    }
  }


  /**
   * Fix permissions in current project
   */
  protected function fixPermissions()
  {
    $this->runTask('project:permissions');
  }
}