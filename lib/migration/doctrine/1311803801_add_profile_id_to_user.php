<?php

class AddProfileIdToUser extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('sf_guard_user', 'profile_id', 'integer', 20, array('unique' => true));
  }
  
  public function postUp()
  {
    Doctrine_Manager::connection()->getDbh()->exec('ALTER TABLE sf_guard_user ADD CONSTRAINT sf_guard_user_profile_id_profile_id FOREIGN KEY (profile_id) REFERENCES profile(id);');
  }

  public function down()
  {
    $this->removeColumn('sf_guard_user', 'profile_id');
  }
}