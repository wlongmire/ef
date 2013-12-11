<?php

/**
 * Doctrine_Template_Calendarable
 *
 * Adds the ability to export a calendar entry for this object.
 *
 * Every object that uses this template must implement a getCalendarableEvents method
 * which returns the events that will be on the calendar.
 * @see getValues()
 * @see getEventRepresentation()
 *
 * If the getCalendarableEvents method requires object relations to exist, it is strongly
 * advised that you implement a getCalendarableQuery method on the corresponding table
 * class. This method should return a query that has all of the necessary joins on it. The
 * method will be called to fetch all records when publishing the calendar. It will save you
 * a lot of extra queries.
 * @see wfCalendar::getQuery
 *
 */
class Doctrine_Template_Calendarable extends Doctrine_Template
{
  /**
   * @param boolean $addUids Should UIDs be used for events (default: false)
   * @return string all of the events defined for this object
   */
  public function calendarEvents($addUids = false)
  {
    $values = $this->getValues();
    if (!$values)
    {
      return '';
    }

    if (is_array(wfToolkit::arrayFirst($values)))
    {
      $events = array();
      foreach($values as $eventValues)
      {
        $event = $this->getEventRepresentation($eventValues, $addUids);
        if ($event)
        {
          $events[] = $event;
        }
      }
      
      return implode("\n", $events);
    }
    else
    {
      return $this->getEventRepresentation($values) ?: '';
    }
  }


  /**
   * Calls a method on the record to get the values for the calendar events that go with this object
   *
   * The values returned can either be a simple array of values for getEventRepresentation() (if you only want one event) or
   * an array of arrays, where each array is a single event.
   *
   * @return array The values for the calendar events
   * @see getEventRepresentation()
   */
  protected function getValues()
  {
    $record = $this->getInvoker();
    $methodName = 'getCalendarableEvents';

    if(method_exists($record, $methodName))
    {
      return $record->$methodName();
    }

    throw new LogicExceptionError('You must implement a ' . $methodName . ' function on ' . get_class($record));
  }


  /**
   * Get the iCal representation of a single event.
   * @param array $values The values for the event. Possible values are:
   *   title (required) - The title of the event. This is the main text that everyone will see
   *   start (required) - The date and time of the event (or when it starts).
   *   end - The date and time that the event ends. If omitted, the event is assumed to be an all-day event.
   *   description - A longer description of the event.
   *   location - The address of the event. This should ideally be google-map-able.
   *   allDay - If true, the event will be treated as an all-day event. If the end time is not set, this is assumed to be true.
   *   timeZone - The timezone of the start and end times. Defaults to "US/Eastern"
   *
   * @param boolean $addUid Should a UID be added for the event. The UID will be of the form id@domain. The id can 
   *                        be in the values or computed by hashing the values. The domain is a placeholder that must 
   *                        be filled in later. See wfCalendar::insertUidDomain
   * @return string The iCal-formatted event
   */
  protected function getEventRepresentation($values, $addUid = false)
  {
    // If we don't have a title and a start time, don't post the event.
    if (!isset($values['start']) || !isset($values['title']))
    {
      return '';
    }

    $template = ($addUid ? "UID:%uid%\n" : '') . "SUMMARY:%title%\nDTSTART;TZID=%timeZone%;VALUE=%dateType%:%start%\n";
    
    $uid = $addUid ? 
           (isset($values['uid']) ? $values['uid'] : md5($values['title'])) : 
           null;
    $allDay = isset($values['allDay']) || !isset($values['end']);
    $format = $allDay ? 'Ymd' : 'Ymd\THis';
    $start = wfToolkit::makeDateTime($values['start']);
    $end = isset($values['end']) ? wfToolkit::makeDateTime($values['end']) : null;

    if ($allDay)
    {
      $template .= "DURATION:P%duration%D\n";
      $duration = 1 + ($end ? date_diff($start, $end)->format('%a') : 0);
    }
    else // event with start and end times
    {
      $template .= "DTEND;TZID=%timeZone%;VALUE=%dateType%:%end%\n";
      $duration = null;
    }

    $description = null;
    if (isset($values['description']))
    {
      $template .= "DESCRIPTION:%description%\n";
      $description = static::sanitizeValue($values['description']);
    }

    $location = null;
    if (isset($values['location']))
    {
      $template .= "LOCATION:%location%\n";
      $location = static::sanitizeValue($values['location']);
    }

    $event = array(
      '%uid%' => $uid . '@%uiddomain%',
      '%title%' => static::sanitizeValue($values['title']),
      '%start%' => $start->format($format),
      '%end%' => $end ? $end->format($format) : null,
      '%duration%' => $duration,
      '%description%' => $description,
      '%location%' => $location,
      '%dateType%' => $allDay ? 'DATE' : 'DATE-TIME',
      '%timeZone%' => isset($values['timeZone']) ? $values['timeZone'] : 'US/Eastern'
    );

    $template = "BEGIN:VEVENT\n" . $template . "END:VEVENT";

    return strtr($template, $event);
  }

  /**
   * Remove line breaks and escape the required characters
   * @param string $value
   * @return string
   */
  protected static function sanitizeValue($value)
  {
    return wfToolkit::stripLineBreaks(strtr($value, array(
      ',' => '\,'
    )));
  }
}
