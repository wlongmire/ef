<?php
class BasewfAddonsComponents extends sfComponents
{
  public function executeFeedbackForm(sfWebRequest $request)
  {
    if (!isset($this->form))
    {
      $formClass = sfConfig::get('app_wf_feedback_form_class', 'wfFeedbackForm');
      $this->form = new $formClass();
    }
    $this->buttonClass = isset($this->buttonClass) ? $this->buttonClass : false;
    if (isset($this->returnUrl))
    {
      $this->form->setDefault('return_url', $this->returnUrl);
    }
  }
}
