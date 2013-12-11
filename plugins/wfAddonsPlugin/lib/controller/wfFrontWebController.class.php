<?php

/*
 *  Overwritten to avoid logging 404s to the apache error log and to email us when a non-404 exception is caught.
 */
class wfFrontWebController extends sfFrontWebController
{
  /**
   * Pretty much an exact copy of parent::dispatch(), but I don't see a better way of doing this.
   */
  public function dispatch()
  {
    try
    {
      sfFilter::$filterCalled = array();

      // determine our module and action
      $request    = $this->context->getRequest();
      $moduleName = $request->getParameter('module');
      $actionName = $request->getParameter('action');

      if (empty($moduleName) || empty($actionName))
      {
        throw new sfError404Exception(sprintf('Empty module and/or action after parsing the URL "%s" (%s/%s).', $request->getPathInfo(), $moduleName, $actionName));
      }

      $this->forward($moduleName, $actionName);
    }
    catch (sfError404Exception $e404) // catch 404 exceptions and pretend we're in test mode so it doesn't log them to the apache error log
    {
      if (!sfConfig::get('sf_debug'))
      {
        $sfTest = sfConfig::get('sf_test');
        sfConfig::set('sf_test', true);
      }

      $e404->printStackTrace();

      if (!sfConfig::get('sf_debug'))
      {
        sfConfig::set('sf_test', $sfTest);
      }
    }
    catch (sfException $e)
    {
      $this->sendEmailForError($e);
      $e->printStackTrace();
    }
    catch (Exception $e)
    {
      $this->sendEmailForError($e);
      sfException::createFromException($e)->printStackTrace();
    }
  }

  protected function sendEmailForError(Exception $e)
  {
    if (sfConfig::get('app_mail_email_on_500', false))
    {
      sfContext::getInstance()->getMailer()->composeAndSend(
        null,
        sfConfig::get('app_mail_error'),
        'Exception Caught in wfFrontWebController - ' . date('Y-m-d H:i:s'),
        $e->__toString()
      );
    }
  }
}