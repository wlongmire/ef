<?php
/**
 * @param integer $number
 * @return string 'odd' if $number is odd, 'even' if $number is even
 */
function parity($number)
{
  return $number % 2 == 1 ? 'odd' : 'even';
}

/**
 * @param integer $number
 * @return string The ordinal form of $number (e.g. 1st, 3rd)
 */
function ordinal_position($number, $locale = 'en_US')
{
  $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
  return $nf->format($number);
}

/**
 * @param integer $number
 * @param integer $bucketSize
 * @return string "first", "middle", or "last"
 */
function ordinal_class($number, $bucketSize)
{
  $remainder = $number % $bucketSize;
  switch($remainder)
  {
    case 1:
      return 'first';
    case 0:
      return 'last';
    default:
      return 'middle';
  }
}

/**
 * @param $unit
 * @param $value
 * @return If value == 1, the $unit is returned. Otherwise, the unit is returned in plural form.
 * If unit is in config value app_wf_special_plurals, that plural will be returned
 */
function pluralize($unit, $value = false)
{
	if ($value == 1)
	{
		return $unit;
	}
	$specialPlurals = sfConfig::get('app_wf_special_plurals');
	if (isset($specialPlurals[$unit]))
	{
		return $specialPlurals[$unit];
	}
  return $unit . 's';
}

/**
 * @deprecated use wfToolkit::makeDateTime
 * @see wfToolkit::makeDateTime
 * @param DateTime|integer|string $date
 * @return DateTime A DateTime object with the date set to $date
 */
function make_date_time($date)
{
  return wfToolkit::makeDateTime($date);
}

/**
 * @param DateTime|integer|string $date
 * @param string $format
 * @return string The date, in the requested format
 */
function wf_date($format, $date = null)
{
  $dateFormat = sfConfig::get('app_wf_date_format_' . $format, $format);
  if (!isset($date))
  {
    $date = new DateTime();
  }
  return wfToolkit::makeDateTime($date)->format($dateFormat);
}

/**
 * Formats a time. Currently does nothing.
 * @param string $time
 * @param array $options
 * @return string A formatted time.
 */
function wf_time($time, $options = array())
{
  $options = array_merge(array(
    'format' => 'g:i a'
  ), $options);
  return date($options['format'], strtotime($time));
}

/**
 * Prints the provided text if the provided condition is true
 * @param boolean $condition
 * @param string $text
 * @return none
 */
function echo_if($condition, $text)
{
  if ($condition)
  {
    echo $text;
  }
}


/**
 * Returns CSS markup hiding an element if the provided condition is met
 * @param boolean $condition If true, the element is hidden, if false, nothing is returned
 * @param boolean $styleTag If true, the 'style="..."' portion of the tag is included. Defaults to true.
 * @return string
 */
function display_none_if($condition, $styleTag = true)
{
  $display = $condition ? 'display: none' : '';
  if ($display && $styleTag)
  {
    $display = 'style="'.$display.'"';
  }
  return $display;
}

/**
 * @param array|Doctrine_Record $record
 * @return array $record itself, if an array, otherwise the result of $record->toParams()
 */
function url_params($record)
{
  return $record instanceof Doctrine_Record ? $record->toParams() : $record;
}

/**
 * This function exists mainly to combat that Doctrine_Collection evaluates to true
 * @param array|Doctrine_Record $record
 * @return boolean True if $record has a non-empty value/Relation $field
 */
function record_has($record, $field)
{
  if (!isset($record[$field]))
  {
    return false;
  }
  if ($record instanceof Doctrine_Record)
  {
    if ($record[$field] instanceof Doctrine_Collection)
    {
      return $record[$field]->count() > 0;
    }
    return (boolean)$record[$field];
  }
  else
  {
    return (boolean)$record[$field];
  }
}

/**
 * @param $fromDate anything make_date_time can handle
 * @param $toDate defaults to today
 * @return integer|null The age if there's a birth_date, null otherwise
 */
function elapsed_years($fromDate, $toDate = null)
{
  $fromDate = make_date_time($fromDate);
  $toDate = make_date_time($toDate);

  return $toDate->format('Y') - $fromDate->format('Y') -
            ($fromDate->format('m') > $toDate->format('m') || 
              ($fromDate->format('m') == $toDate->format('m') && $fromDate->format('d') > $toDate->format('d'))
            ); //last expression will evaluate to 1 if true, 0 otherwise
}