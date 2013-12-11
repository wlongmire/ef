<?php
class wfValidatorState extends sfValidatorChoice
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
      $usStates = array_keys($doctrineValidator->getStates());
      $canadaStates = array('ON', 'QC', 'BC', 'AB', 'MB', 'SK', 'NS', 'NB', 'NL', 'PE', 'NT', 'YT', 'NU');
      $this->states = array_merge($usStates, $canadaStates);
    }

    return $this->states;
  }
}