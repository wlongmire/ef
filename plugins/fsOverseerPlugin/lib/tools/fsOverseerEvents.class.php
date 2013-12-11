<?php

class fsOverseerEvents
{
  // command.post_command
  public static function listenToCommandPostCommandEvent(sfEvent $event)
  {
    $task = $event->getSubject();
    if ($task->getFullName() === 'project:permissions')
    {
      $lockDir = fsOverseerTools::getLockDir();
      $task->getFilesystem()->mkdirs($lockDir);
      $task->getFilesystem()->chmod($lockDir, 0777);
      $fileFinder = sfFinder::type('file');
      $task->getFilesystem()->chmod($fileFinder->in($lockDir), 0666);
    }
  }
}

