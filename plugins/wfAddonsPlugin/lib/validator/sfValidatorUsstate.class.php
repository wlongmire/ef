<?php
class sfValidatorUsstate extends sfValidatorChoice
{
	protected function configure($options = array(), $attributes = array())
	{
		parent::configure($options, $attributes);
		$this->setOption('choices', new sfCallable(array($this, 'getStateChoices')));
	}

  public function getStateChoices()
  {
    if (!isset($this->states))
    {
      $doctrineValidator = new Doctrine_Validator_Usstate();
      $this->states = array_keys($doctrineValidator->getStates());
    }

    return $this->states;
  }
}