<?php

class AddProfileUrls extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec('CREATE TABLE profile_url (id BIGINT AUTO_INCREMENT, profile_id BIGINT, type VARCHAR(20) NOT NULL, url VARCHAR(255), INDEX profile_id_idx (profile_id), PRIMARY KEY(id)) ENGINE = INNODB;');
    $dbh->exec('ALTER TABLE profile_url ADD CONSTRAINT profile_url_profile_id_profile_id FOREIGN KEY (profile_id) REFERENCES profile(id);');
    $dbh->exec('INSERT INTO profile_url (type, url, profile_id) SELECT "personal", url, id FROM profile WHERE url IS NOT NULL AND url != ""');
    $dbh->exec('ALTER TABLE profile DROP COLUMN url');
  }

  public function down()
  {
  }
}
