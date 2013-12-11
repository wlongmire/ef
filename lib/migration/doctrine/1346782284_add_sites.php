<?php

class AddSites extends Doctrine_Migration_Base
{
  public function up()
  {      
    $sites = array(
      'eventsfilter.com' => array(
          'name' => 'eventsfilter',
          'org_abbr' => 'EventsFilter',
          'org_name' => 'EventsFilter',
          'theme' => 'eventsfilter'
      ),
      'phlocal.com' => array(
          'name' => 'phlocal',
          'org_abbr' => 'PHLocal',
          'org_name' => 'PHLocal'
      ),
      'pifva.org' => array(
          'name' => 'pifva',
          'org_abbr' => 'PIFVA',
          'org_name' => 'PIFVA',
          'theme' => 'pifva'
      ),
      'theartblog.org' => array(
          'name' => 'theartblog',
          'org_abbr' => 'The Art Blog',
          'org_name' => 'The Art Blog',
          'theme' => 'theartblog'
      ),
      'livearts-fringe.org' => array(
        'name' => 'livearts-fringe',
        'org_abbr' => 'Live Arts & Fringe',
        'org_name' => 'Live Arts Festival & Philly Fringe'
      ),
      'designphiladelphia.org' => array(
          'name' => 'designphiladelphia',
          'org_abbr' => 'Design Phila',
          'org_name' => 'Design Philadelphia'
      ),
      'comedy.phlocal.com' => array(
          'name' => 'comedyphlocal',
          'org_abbr' => 'Comedy PHLocal Demo',
          'org_name' => 'Comedy PHLocal Demo',
          'theme' => 'pifva'
      ),
      'dance.phlocal.com' => array(
          'name' => 'dancephlocal',
          'org_abbr' => 'Dance PHLocal Demo',
          'org_name' => 'Dance PHLocal Demo',
          'theme' => 'pifva'
      ),
      'theater.phlocal.com' => array(
          'name' => 'theaterphlocal',
          'org_abbr' => 'Theater PHLocal Demo',
          'org_name' => 'Theater PHLocal Demo',
          'theme' => 'pifva'
      )
    );
    
    foreach($sites as $domain => $values)
    {      
      $site = new Site();
      $site->fromArray($values);
      $site->domain = $domain;
      $site->save();
    }
  }

  public function down()
  {
  }
}
