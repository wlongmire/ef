<?php

class AddVenueTypes extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::connection();
    $dbh->exec('CREATE TABLE venue_type (id BIGINT AUTO_INCREMENT, name VARCHAR(50) UNIQUE, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;');
    $dbh->exec('CREATE TABLE venue_venue_type (venue_id BIGINT, venue_type_id BIGINT, PRIMARY KEY(venue_id, venue_type_id)) ENGINE = INNODB;');
    $dbh->exec('ALTER TABLE venue_venue_type ADD CONSTRAINT venue_venue_type_venue_type_id_venue_type_id FOREIGN KEY (venue_type_id) REFERENCES venue_type(id) ON UPDATE CASCADE ON DELETE CASCADE;');
    $dbh->exec('ALTER TABLE venue_venue_type ADD CONSTRAINT venue_venue_type_venue_id_venue_id FOREIGN KEY (venue_id) REFERENCES venue(id) ON UPDATE CASCADE ON DELETE CASCADE;');
  }

  public function down()
  {
  }
}
