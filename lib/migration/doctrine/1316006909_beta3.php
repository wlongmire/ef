<?php

class Beta3 extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('event', 'media_item_id', 'integer', 20);
    $dbh = Doctrine_Manager::connection()->getDbh();
    $dbh->exec('CREATE TABLE event_owner (event_id BIGINT NOT NULL, user_id BIGINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(event_id, user_id)) ENGINE = INNODB;');
    $dbh->exec('CREATE TABLE profile_owner (profile_id BIGINT NOT NULL, user_id BIGINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(profile_id, user_id)) ENGINE = INNODB;');
    $dbh->exec('CREATE TABLE venue_owner (venue_id BIGINT NOT NULL, user_id BIGINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(venue_id, user_id)) ENGINE = INNODB;');
    $dbh->exec('ALTER TABLE event_owner ADD CONSTRAINT event_owner_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON UPDATE CASCADE ON DELETE CASCADE;');
    $dbh->exec('ALTER TABLE event_owner ADD CONSTRAINT event_owner_event_id_event_id FOREIGN KEY (event_id) REFERENCES event(id) ON UPDATE CASCADE ON DELETE CASCADE;');
    $dbh->exec('ALTER TABLE venue_owner ADD CONSTRAINT venue_owner_venue_id_venue_id FOREIGN KEY (venue_id) REFERENCES venue(id) ON UPDATE CASCADE ON DELETE CASCADE;');
    $dbh->exec('ALTER TABLE venue_owner ADD CONSTRAINT venue_owner_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON UPDATE CASCADE ON DELETE CASCADE;');
    $dbh->exec('ALTER TABLE profile_owner ADD CONSTRAINT profile_owner_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON UPDATE CASCADE ON DELETE CASCADE;');
    $dbh->exec('ALTER TABLE profile_owner ADD CONSTRAINT profile_owner_profile_id_profile_id FOREIGN KEY (profile_id) REFERENCES profile(id) ON UPDATE CASCADE ON DELETE CASCADE;');
  }

  public function down()
  {
  }
}
