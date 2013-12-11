<?php

class wfCalendar
{
  protected $constraints = array();
  protected $error;
  protected $uidDomain;
  protected $name;

  public function __construct($model)
  {
    $this->model = $model;
  }
  
  public function getError()
  {
    return $this->error;
  }
  
  public function setUidDomain($domain)
  {
    $this->uidDomain = $domain;
  }
  
  public function setName($name)
  {
    $this->name = $name;
  }

  public function addConstraint($field, $value)
  {
    $this->constraints[$field] = $value;
  }

  /**
   * @return boolean True if the model is real and Calendarable. False otherwise.
   */
  public function checkModel()
  {
    if(!Doctrine::isValidModelClass($this->model))
    {
      $this->error = $model . ' does not exist.';
      return false;
    }

    if (!Doctrine::getTable($this->model)->hasTemplate('Calendarable'))
    {
      $this->error = $model . ' is not calendarable.';
      return false;
    }

    return true;
  }

  /**
   * Get the query that should be used to find records for events.
   * @return Doctrine_Query
   */
  public function getQuery()
  {
    $table = Doctrine::getTable($this->model);
    $method = 'getCalendarableQuery';
    $query = method_exists($table, $method) ? $table->$method() : $table->createQuery();
    $rootAlias = $query->getRootAlias();

    foreach($this->constraints as $field => $value)
    {
      $query->addWhere($rootAlias . '.' . $field . ' = ?', $value);
    }
    return $query;
  }

  /**
   * Get the calendar for the model
   * @return string
   */
  public function getCalendar()
  {
    $records = $this->getQuery()->execute();
    $addUids = isset($this->uidDomain);
    
    $events = '';
    foreach($records as $record)
    {
      $recordEvents = $record->calendarEvents($addUids);
      if ($recordEvents)
      {
        $events .= $recordEvents . "\n";
      }
    }
    
    if ($addUids)
    {
      $events = $this->insertUidDomain($events, $this->uidDomain);
    }
    
    return $this->fixLineEndings($this->addHeaderFooter($events));
  }

  /**
   * @return string The required iCal header
   */
  protected function header()
  {
    return "BEGIN:VCALENDAR
VERSION:2.0
PRODID:FlickSwitch
CALSCALE:GREGORIAN
METHOD:PUBLISH
" . ($this->name ? "X-WR-CALNAME;VALUE=TEXT:" . $this->name . "\n" : '') . 
"BEGIN:VTIMEZONE
TZID:US/Eastern
BEGIN:DAYLIGHT
TZOFFSETFROM:-0500
TZOFFSETTO:-0400
DTSTART:20070311T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU
TZNAME:EDT
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:-0400
TZOFFSETTO:-0500
DTSTART:20071104T020000
RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU
TZNAME:EST
END:STANDARD
END:VTIMEZONE
";
  }


  /**
   * @return string The required iCal footer
   */
  protected function footer()
  {
    return 'END:VCALENDAR';
  }

  /**
   * Prepend the ical header and append the ical footer
   * @param string $body
   * @return string
   */
  protected function addHeaderFooter($body)
  {
    return $this->header() . $body . $this->footer();
  }

  /**
   * Fill in domain on all UID fields
   * @param string $body
   * @param string $domain
   * @return string
   */
  protected function insertUidDomain($body, $domain)
  {
    return str_replace('%uiddomain%', $domain, $body);
  }

  /**
   * Ical requires that all lines end in \r\n
   * @param string $vcal
   * @return string
   */
  protected function fixLineEndings($vcal)
  {
    return preg_replace('/\n/', "\r\n", $vcal);
  }

}