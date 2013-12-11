<?php

class fsCopyTask extends fsBaseTask
{
  protected $outputBuffer, $errorBuffer;

  protected function configure()
  {
    $this->namespace        = 'fs';
    $this->name             = 'copy';
    $this->briefDescription = 'Copies a file from or to a remote server, using the settings in properties.ini';

    $this->addArgument('direction', sfCommandArgument::REQUIRED, '"from" or "to"');
    $this->addArgument('server', sfCommandArgument::REQUIRED, 'A server name as listed in properties.ini (examples: staging, production)');
    $this->addArgument('source', sfCommandArgument::REQUIRED, 'The file to copy (either absolute path or path relative to the root directory)');
    $this->addArgument('dest', sfCommandArgument::OPTIONAL, 'Where to copy the file to (defaults to source)');

    $this->addOption('method', null, sfCommandOption::PARAMETER_OPTIONAL, 'rsync or scp', 'rsync');
    $this->addOption('method-options', null, sfCommandOption::PARAMETER_OPTIONAL, 'Extra options to pass to the copy command');
    $this->addOption('scp-options', null, sfCommandOption::PARAMETER_OPTIONAL, 'Extra options specific to scp', '');
    $this->addOption('rsync-options', null, sfCommandOption::PARAMETER_OPTIONAL, 'Extra options specific to rsync', '--progress');
    $this->addOption('skip-exists-check', null, sfCommandOption::PARAMETER_NONE, 'Skip check to see if remote source exists');
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->fixArguments($arguments);
    $properties = $this->getServerProperties($arguments['server'], array('host', 'dir'));

    if ($arguments['direction'] == 'from'
        &&
        !$options['skip-exists-check']
        &&
        !$this->remoteSourceExists($properties, $arguments['source']))
    {
      $this->logSection($this->getName(), 'Remote file not found: ' . $arguments['source'], null, 'ERROR');
      return;
    }

    $command = $this->buildCopyCommand($arguments, $options, $properties);

    $this->doCopy($command, $options['quiet']);
  }

  protected function fixArguments(&$arguments)
  {
    if ($arguments['direction'] == 'to' && !realpath($arguments['source']))
    {
      throw new InvalidArgumentException('Make sure that your source path is either absolute or relative to the root symfony directory. ' .
                                         'To avoid this problem, always run this command from the root dir.');
    }

    if (!$arguments['dest'])
    {
      $arguments['dest'] = $arguments['source'];
      if (strpos($arguments['dest'], '/') === 0)
      {
        $arguments['dest'] = substr($arguments['dest'], strlen(sfConfig::get('sf_root_dir')) + 1); // +1 to remove leading slash
      }
    }
  }

  /**
   * @param array $properties
   * @param string $source
   * @return boolean True if file/directory exists, false otherwise.
   */
  protected function remoteSourceExists($properties, $source)
  {
    $remoteServer = $this->getConnectString($properties['host'], null, isset($properties['user']) ? $properties['user'] : null);
    $target = strpos($source,'/') === 0 ? $source : $properties['dir'] . '/' . $source;
    
    $command = 'if ssh ' . $remoteServer . ' test -e ' . $target . '; then echo 1; else echo 0; fi';
    
    $this->logSection($this->getName(), $command);
    return (boolean)exec('if ssh ' . $remoteServer . ' test -e ' . $target . '; then echo 1; else echo 0; fi');
  }

  protected function buildCopyCommand($arguments, $options, $properties)
  {
    $port = isset($properties['port']) ? $properties['port'] : null;

    switch($arguments['direction'])
    {
      case 'from':
        $dest = $arguments['dest'];
        $source = $this->getConnectString($properties['host'], $properties['dir'],
                                          isset($properties['user']) ? $properties['user'] : null, $arguments['source']);
        break;

      case 'to':
        $source = $arguments['source'];
        $dest = $this->getConnectString($properties['host'], $properties['dir'],
                                        isset($properties['user']) ? $properties['user'] : null, $arguments['dest']);
        break;

      default:
        throw new InvalidArgumentException('Direction must be "from" or "to"');
    }
    
    switch($options['method'])
    {
      case 'scp':
        $extraOptions = trim($options['method-options'] . ' ' . $options['scp-options']);
        return strtr('scp%port%%options% %source% %dest%', array(
          '%port%' => ($port !== null ? (' -P ' . (int)$port) : ''),
          '%options%' => $extraOptions ? ' '.$extraOptions : '',
          '%source%' => escapeshellarg($source),
          '%dest%' => escapeshellarg($dest),
    ));

      case 'rsync':
        $extraOptions = trim($options['method-options'] . ' ' . $options['rsync-options']);
        return strtr('rsync%port%%options% %source% %dest%', array(
          '%port%' => ($port !== null ? (' -e "ssh -p ' . (int)$port) . '"' : ''),
          '%options%' => $extraOptions ? ' '.$extraOptions : '',
          '%source%' => escapeshellarg($source),
          '%dest%' => escapeshellarg($dest),
        ));

      default:
        throw new InvalidArgumentException('Unknown method: "' . $options['method'] . '"');
    }
  }

  protected function doCopy($command, $quiet)
  {
    try
    {
      $this->getFilesystem()->execute($command, $quiet ? null : array($this, 'logOutput'), array($this, 'logErrors'));
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