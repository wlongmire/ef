<?php
class BasewfFeedbackForm extends wfFormSymfonySavable
{
  public function setup()
  {
    $this->setWidgets(array(
        'name' => new sfWidgetFormInputText(),
        'email' => new sfWidgetFormInputText(),
        'message' => new wfWidgetFormJqueryTextarea(),
        'return_url' => new sfWidgetFormInputHidden()
    ));

    $this->setValidators(array(
        'name' => new sfValidatorString(array('max_length' => 100)),
        'email' => new wfValidatorStringEmail(),
        'message' => new sfValidatorString(array('max_length' => 10000)),
        'return_url' => new sfValidatorUrl(array('required' => false))
    ));

    $this->getWidgetSchema()->setNameFormat('wf_feedback[%s]');
  }

  protected function doSave($values)
  {
    $mailer = sfContext::getInstance()->getMailer();
    $message = sprintf("%s (%s) wrote:\n\n%s", $values['name'], $values['email'], $values['message']);
    $mailer->composeAndSend(null, 'team@flickswitch.cc', 'Feedback Submission', $message);
  }
}