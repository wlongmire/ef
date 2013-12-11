<?php

class FixGlobalSlugs extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    foreach(array(
      'eventsfilter.com' => 'EventsFilter',
      'pifva.org' => 'PIFVA',
      'theartblog.org' => 'The Art Blog',
      'livearts-fringe.org' => 'Live Arts Festival & Philly Fringe',
      'phlocal.com' => 'PHLocal'
    ) as $domain => $name)
    {
      $stem = '_global_virtual_page';
      $dbh->exec(sprintf('UPDATE a_page SET slug = "%s" WHERE slug = "%s"', $domain . $stem, $name . $stem));
    }
  }

  public function down()
  {
  }
}
