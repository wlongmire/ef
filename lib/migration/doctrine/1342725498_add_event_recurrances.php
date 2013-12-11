<?php

class AddEventRecurrances extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec("CREATE TABLE event_recurrance (id BIGINT AUTO_INCREMENT, event_id BIGINT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, sunday TINYINT(1) DEFAULT '0' NOT NULL, monday TINYINT(1) DEFAULT '0' NOT NULL, tuesday TINYINT(1) DEFAULT '0' NOT NULL, wednesday TINYINT(1) DEFAULT '0' NOT NULL, thursday TINYINT(1) DEFAULT '0' NOT NULL, friday TINYINT(1) DEFAULT '0' NOT NULL, saturday TINYINT(1) DEFAULT '0' NOT NULL, INDEX event_id_idx (event_id), PRIMARY KEY(id)) ENGINE = INNODB;");
    $dbh->exec("ALTER TABLE event_recurrance ADD CONSTRAINT event_recurrance_event_id_event_id FOREIGN KEY (event_id) REFERENCES event(id) ON UPDATE CASCADE ON DELETE CASCADE;");
  }

  public function down()
  {
  }
}
