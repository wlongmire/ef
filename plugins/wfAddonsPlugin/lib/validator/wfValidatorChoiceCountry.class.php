<?php
class wfValidatorChoiceCountry extends sfValidatorChoice
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setOption('choices', array_keys(Doctrine_Validator_Country::getCountries()));
  }
}