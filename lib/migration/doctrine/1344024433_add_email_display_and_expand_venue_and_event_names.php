<?php

class AddEmailDisplayAndExpandVenueAndEventNames extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec('ALTER TABLE profile ADD COLUMN display_email TINYINT(1) NOT NULL DEFAULT 0');
    $dbh->exec('ALTER TABLE event MODIFY COLUMN name VARCHAR(100) NOT NULL');
    $dbh->exec('ALTER TABLE venue MODIFY COLUMN name VARCHAR(100) NOT NULL');
    $dbh->exec('UPDATE a_area SET name = "location-help" WHERE name = "profile-edit-info"');
  }

  public function down()
  {
  }
}
