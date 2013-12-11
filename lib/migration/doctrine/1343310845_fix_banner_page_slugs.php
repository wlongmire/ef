<?php

class FixBannerPageSlugs extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec('DELETE FROM a_page WHERE id NOT IN (SELECT page_id FROM a_area) AND id > 50');
    $dbh->exec('UPDATE a_page SET slug = CONCAT("phlocal.com-", slug) WHERE id IN (SELECT page_id FROM a_area WHERE name = "details-banner")');
    $dbh->exec('UPDATE a_slot SET value = REPLACE(value, "&amp;alpha=1", "&amp;sort=alpha") WHERE value LIKE "%amp;alpha=1%"');
  }

  public function down()
  {
  }
}
