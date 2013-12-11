<?php

class PublicEventAdministration extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec('ALTER TABLE event ADD COLUMN ticket_url VARCHAR(255), ADD COLUMN is_published TINYINT(1) DEFAULT "0" NOT NULL, ADD COLUMN suggested_venue_name VARCHAR(50)');
    $dbh->exec('ALTER TABLE event MODIFY COLUMN venue_id BIGINT');
    $dbh->exec('ALTER TABLE event ADD index is_published_idx (is_published)');
    $dbh->exec("ALTER TABLE event_type ADD COLUMN is_daily TINYINT(1) DEFAULT '0' NOT NULL");
    $dbh->exec('UPDATE event SET is_published = 1');
    $dbh->exec('UPDATE event_type SET is_daily = 1 WHERE name = "Opportunities" OR name = "Exhibits"');
  }

  public function down()
  {
  }
}
