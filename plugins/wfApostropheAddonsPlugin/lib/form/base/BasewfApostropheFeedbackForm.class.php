<?php
class BasewfApostropheFeedbackForm extends wfFeedbackForm
{
  public function configure()
  {
    parent::configure();
    
    $this->setOption('cancel_button', '<a href="%s" class="a-btn icon a-cancel big"><span class="icon"></span>Cancel</a>');
    $this->setOption('submit_button', '<a href="javascript:;" onclick="$(this).closest(\'form\').submit();" class="a-btn icon a-submit big a-email a-show-busy"><span class="icon"></span>Send</a>');

    $this->getWidget('name')
      ->setAttribute('data-placeholder', 'Name')
      ->setAttribute('class', 'required')
      ->setOption('label', false);
    $this->getWidget('email')
      ->setAttribute('data-placeholder', 'Email')
      ->setAttribute('class', 'required email')
      ->setOption('label', false);

    $this->getWidget('message')
      ->setAttribute('class', 'required');
  }
}