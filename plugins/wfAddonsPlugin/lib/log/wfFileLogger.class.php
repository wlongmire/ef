<?php

/**
 * wfFileLogger extends sfFileLogger to add GET/POST and XML HTTP info and to log all submitted parameters
 *
 * @package    symfony
 * @subpackage log
 * @author     Alex Grintsvayg
 */
class wfFileLogger extends sfFileLogger
{
  protected
    $timeFormat = '%b %d %Y %H:%M:%S',
    $tempDisableLogging = false; // so we can disable logging while we get the sfGuardUser (if we dont, it causes an infinite logging loop)

  /**
   * Logs a message.
   *
   * @param string $message   Message
   * @param string $priority  Message priority
   */
  protected function doLog($message, $priority)
  {
    if ($this->tempDisableLogging)
    {
      return;
    }

    $params = array_merge(array(
      'time'         => strftime($this->timeFormat), // time must be first the param in the array
      'message'      => $message,
      'priority'     => $this->getPriority($priority)
    ), $this->getLogParams());

    flock($this->fp, LOCK_EX);
    fwrite($this->fp, json_encode($params).PHP_EOL);
    flock($this->fp, LOCK_UN);
  }

  protected function getLogParams()
  {
    $context = sfContext::getInstance();
    $request = $context->getRequest();
    $params = array();

    if ($request)
    {
      $params['username'] = $this->safeGetUsername($context->getUser());
      $params['module'] = $context->getModuleName();
      $params['action'] = $context->getActionName();
      $params['method'] = ($request->isXmlHttpRequest() ? 'X-' : '') . $request->getMethod();
      $params['referer'] = $request->getReferer() ?: '-';
      $params['useragent'] = $request->getHttpHeader('User-Agent') ?: '-';
      $params['url'] = $request->getUri() ?: '-';
      $params['ip'] = $request->getHttpHeader('addr','remote');
      $params['params'] = $request instanceof wfWebRequest ? ($request->paramsToString(true) ?: '-') : '-';
    }

    return $params;
  }

  /**
   * Gets the username without causing an infinite logging loop.
   * @param myUser $user
   * @return string
   */
  protected function safeGetUsername($user)
  {
    $this->tempDisableLogging = true;
    $username = '';
    if ($user instanceof myUser && $user->isAuthenticated() && $user->guardUserExists())
    {
      $username = $user->getUsername();
    }
    $this->tempDisableLogging = false;
    return $username;
  }
}
