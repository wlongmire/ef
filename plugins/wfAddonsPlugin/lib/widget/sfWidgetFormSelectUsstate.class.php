<?php
class sfWidgetFormSelectUsstate extends sfWidgetFormSelect
{
	protected function configure($options = array(), $attributes = array())
	{
		parent::configure($options, $attributes);

    $this->addOption('with_empty', false);
		$this->setOption('choices', new sfCallable(array($this, 'getStateChoices')));
    
    $defaultState = sfConfig::get('app_wf_default_state');
    if ($defaultState)
    {
      $this->setDefault($defaultState);
    }
	}

  public function getStateChoices()
  {
    if (!isset($this->states))
    {
      $doctrineValidator = new Doctrine_Validator_Usstate();
      $states = array_keys($doctrineValidator->getStates());
      sort($states);
      $this->states = array_combine($states, $states);
    }

    return $this->getOption('with_empty') ? array('' => '') + $this->states : $this->states;
  }
}