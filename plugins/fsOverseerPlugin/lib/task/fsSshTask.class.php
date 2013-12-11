<?php

class fsSshTask extends fsBaseTask
{
  protected $outputBuffer, $errorBuffer;

  protected function configure()
  {
    $this->namespace        = 'fs';
    $this->name             = 'ssh';
    $this->briefDescription = 'Runs a command on a remote server using the settings in properties.ini';

    $this->addArgument('server', sfCommandArgument::REQUIRED, 'A server name as listed in properties.ini (examples: staging, production)');
    $this->addArgument('command', sfCommandArgument::REQUIRED, 'The command to execute');
    
    $this->addOption('task', null, sfCommandOption::PARAMETER_NONE, 'The command given is the task name of a task to run');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $properties = $this->getServerProperties($arguments['server'], array('host', 'dir'));
    $connect = $this->getConnectString($properties['host'], null, isset($properties['user']) ? $properties['user'] : null);

    $port = isset($properties['port']) ? $properties['port'] : null;
    //$binary = basename($_SERVER['SCRIPT_FILENAME']);

    $ssh = strtr('ssh %connect%%port% %command%', array(
      '%connect%' => $connect,
      '%port%' => ($port !== null ? (' -p ' . (int)$port) : ''),
      '%command%' => escapeshellarg($options['task'] ? 
                                    $properties['dir'] . '/symfony ' . $arguments['command'] :
                                    $arguments['command'])
    ));

    try
    {
      $this->getFilesystem()->execute($ssh, $options['quiet'] ? null : array($this, 'logOutput'), array($this, 'logErrors'));
      $this->clearBuffers();
    }
    catch (RuntimeException $e)
    {
      $this->clearBuffers();
      $this->logSection($this->getName(), 'Command exited with code ' . $e->getCode(), null, 'ERROR');
      throw $e;
    }
  }

  // taken from sfProjectDeployTask
  public function logOutput($output)
  {
    if (false !== $pos = strpos($output, "\n"))
    {
      $this->outputBuffer .= substr($output, 0, $pos);
      $this->log($this->outputBuffer);
      $this->outputBuffer = substr($output, $pos + 1);
    }
    else
    {
      $this->outputBuffer .= $output;
    }
  }

  // taken from sfProjectDeployTask
  public function logErrors($output)
  {
    if (false !== $pos = strpos($output, "\n"))
    {
      $this->errorBuffer .= substr($output, 0, $pos);
      $this->log($this->formatter->format($this->errorBuffer, 'ERROR'));
      $this->errorBuffer = substr($output, $pos + 1);
    }
    else
    {
      $this->errorBuffer .= $output;
    }
  }

  // taken from sfProjectDeployTask
  protected function clearBuffers()
  {
    if ($this->outputBuffer)
    {
      $this->log($this->outputBuffer);
      $this->outputBuffer = '';
    }

    if ($this->errorBuffer)
    {
      $this->log($this->formatter->format($this->errorBuffer, 'ERROR'));
      $this->errorBuffer = '';
    }
  }
}