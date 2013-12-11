<?php

/**
 * Project filter form base class. Adds the following options:
 * * alias: A default alias to use when option query is not set
 *
 * @package    withfit
 * @subpackage filter
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: sfDoctrineFormFilterBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class wfFormFilterDoctrine extends sfFormFilterDoctrine
{
  public function setup()
  {
    $this->disableLocalCSRFProtection();
  }

  /**
   * Selectively binds the form with input values. Unlike traditional bind, selectiveBind will only override defaults
   * if the parameter is present.
   *
   * Can be used in conjunction with getValuesWithoutDefaults for smaller permalinked URLs
   *
   * It triggers the validator schema validation.
   *
   * @param array $taintedValues  An array of input values
   * @param array $fields         The fields that should be selectively bound. Must be specified explicitly to avoid issuse with checkboxes.
   * @see sfForm::bind
   * @see BaseFormFilterDoctrine::getValuesWithoutDefaults
   */
  public function selectiveBind(array $taintedValues = null, array $fields = null)
  {
    foreach($fields as $field)
    {
      if (!isset($taintedValues[$field]))
      {
        $taintedValues[$field] = $this->getDefault($field);
      }
    }
    $this->bind($taintedValues);
  }

  /**
   * Returns the array of cleaned values, excluding those cleaned values that match form level defaults.
   *
   * If the form is not bound, it returns an empty array.
   *
   * @return array An array of cleaned values excluding those values that match defaults.
   */
  public function getValuesWithoutDefaults()
  {
    if (!$this->isBound)
    {
      return array();
    }
    $valuesWithoutDefaults = array();
    foreach($this->getValues() as $field => $value)
    {
      if ($value != $this->getDefault($field))
      {
        $valuesWithoutDefaults[$field] = $value;
      }
    }
    return $valuesWithoutDefaults;
  }
  
  /**
   * Adds a page widget and validator to the form. Sets the default page to 1.
   */
  public function addPageWidgetAndValidator()
  {
    $this->setWidget('page', new sfWidgetFormInputHidden(array(), array('class' => 'page')));
    $this->setValidator('page', new sfValidatorInteger(array('min' => 0, 'required' => false)));
    $this->setDefault('page', 1);
  }

  /**
   * Returns the minimum length of the search field for the current form
   * @return integer
   */
  public function getMinimumQueryLength()
  {
    return $this->getOption('min_query_length', sfConfig::get('app_search_min_query_length', 10));
  }
  

  /**
   * @param string $mode A Doctrine hydration mode
   * @return array|Doctrine_Collection depending on the hydration mode
   */
  public function getResults($mode = Doctrine::HYDRATE_RECORD)
  {
    return $this->getQuery()->setHydrationMode($mode)->execute();
  }

  /**
   * Calls fetch one rather than execute
   * @param string $mode A Doctrine hydration mode
   * @return array|Doctrine_Record depending on the hydration mode
   */
  public function getFirst($mode = Doctrine::HYDRATE_RECORD)
  {
    return $this->getQuery()->setHydrationMode($mode)->fetchOne();
  }

  /**
   * Returns a pager for the current form with the query returned by $this->getQuery(). If the form is invalid, it will look for a
   * "getDefaultQuery" function. The pager will be set to the "page" value of the current filter. The pager's record limits and page limits
   * are set by the config values 'app_UNDERSCORED_CLASS_NAME_max_per_age' and 'app_search_max_record_limit' respectively.
   *
   * The returned pager is initiated. The pager class can be overridden by setting the 'pager_class' option.
   * 
   * @return sfDoctrinePager|null Null is only returned if the filter is invalid and method "getDefaultQuery" doesn't exist.
   * The pager class can be set by the pager_class option.
   */
  public function getPager($init = true)
  {
    $modelName = $this->getModelName();
    $underscoredModelName = wfToolkit::underscore($modelName);
    $maxPerPage = sfConfig::get('app_' . $underscoredModelName . '_max_per_page', sfConfig::get('app_search_max_per_page', 10));
    $pagerClass = $this->getOption('pager_class', 'sfDoctrinePager');
    $pager = new $pagerClass($this->getModelName(), $maxPerPage);
    if ($this->isValid())
    {
      $pager->setQuery($this->getQuery());
    }
    else if (method_exists($this, 'getDefaultQuery'))
    {
      $pager->setQuery($this->getDefaultQuery());
    }
    else
    {
      return null;
    }

    $page = $this->getValue('page');
    if ($page)
    {
      $pager->setPage($page);
    }
    $pager->setMaxRecordLimit(sfConfig::get('app_search_max_record_limit'));
    if ($init)
    {
      $pager->init();
    }

    return $pager;
  }

   /**
   * Builds a Doctrine query with processed values. Overridden to use option "alias" when option "query" is not provied.
   *
   * @see sfFormFilterDoctrine::doBuildQuery
   * @param  array $values
   * @return Doctrine_Query
   */
  protected function doBuildQuery(array $values)
  {
    if (!isset($this->options['query']))
    {
      $this->options['query'] = $this->getTable()->createQuery($this->getOption('alias', 'r'));
    }
    return parent::doBuildQuery($values);
  }
}