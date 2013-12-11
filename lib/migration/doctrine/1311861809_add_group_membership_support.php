<?php

class AddGroupMembershipSupport extends Doctrine_Migration_Base
{
  public function up()
  {
    Doctrine_Manager::connection()->getDbh()->exec('CREATE TABLE profile_group_member (group_profile_id BIGINT, member_profile_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(group_profile_id, member_profile_id)) ENGINE = INNODB;');
    Doctrine_Manager::connection()->getDbh()->exec('ALTER TABLE profile_group_member ADD CONSTRAINT profile_group_member_member_profile_id_profile_id FOREIGN KEY (member_profile_id) REFERENCES profile(id);');
    Doctrine_Manager::connection()->getDbh()->exec('ALTER TABLE profile_group_member ADD CONSTRAINT profile_group_member_group_profile_id_profile_id FOREIGN KEY (group_profile_id) REFERENCES profile(id);');
  }

  public function down()
  {
  }
}
