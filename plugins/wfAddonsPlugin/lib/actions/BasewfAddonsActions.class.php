<?php
class BasewfAddonsActions extends wfActions
{
  public function executeFeedbackSubmit(wfWebRequest $request)
  {
    $formClass = sfConfig::get('app_wf_feedback_form_class', 'wfFeedbackForm');
    $this->form = new $formClass();
    if ($request->isXmlHttpRequest())
    {
      $this->isJSON();
    }
    if ($request->isMethod('post') && $this->form->bindAndSave($request[$this->form->getName()]))
    {
      $this->getUser()->setFlash('wf-feedback-success', 'Message sent.');
      if ($request->isXmlHttpRequest())
      {
        return $this->renderJSON(array('success' => true));
      }
      $this->redirect($this->form->getValue('return_url'));
    }
    if ($request->isXmlHttpRequest())
    {
      return $this->renderJSON(array('success' => false));
    }
  }

  public function executeApcClear(sfWebRequest $request)
  {
    $settings = parse_ini_file(sfConfig::get('sf_root_dir') . "/config/properties.ini", true);
    if ($settings === false)
    {
      throw new sfException("Cannot find config/properties.ini");
    }

    if (!isset($settings['sync']) || !isset($settings['sync']['password']))
    {
      throw new sfException('Sync section not configured or missing password in properties.ini');
    }

    $password = $settings['sync']['password'];
    if ($request['password'] !== $password)
    {
      throw new sfException('Bad password');
    }

    apc_clear_cache();
    apc_clear_cache('user');
    apc_clear_cache('opcode');

    return $this->renderJSON(array('success' => true));
  }
}