<?php
class sfWidgetFormSelectCountry extends sfWidgetFormSelect
{
	protected function configure($options = array(), $attributes = array())
	{
		parent::configure($options, $attributes);
		$countries = Doctrine_Validator_Country::getCountries();
		asort($countries);
		$countries = array('' => '', 'us' => $countries['us']) + $countries;
		$this->setOption('choices', $countries);
	}
}