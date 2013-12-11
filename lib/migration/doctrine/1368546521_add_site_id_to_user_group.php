<?php

class AddSiteIdToUserGroup extends Doctrine_Migration_Base
{
  public function up()
  {
    $commands = array(
      'ALTER TABLE sf_guard_user_group DROP FOREIGN KEY sf_guard_user_group_group_id_sf_guard_group_id',
      'ALTER TABLE sf_guard_user_group DROP FOREIGN KEY sf_guard_user_group_user_id_sf_guard_user_id',
      'ALTER TABLE sf_guard_user_group DROP PRIMARY KEY',        
      'ALTER TABLE sf_guard_user_group ADD COLUMN site_id BIGINT',
      'ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE',
      'ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_site_id_site_id FOREIGN KEY (site_id) REFERENCES site(id) ON UPDATE CASCADE ON DELETE CASCADE',
      'ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE',
      "ALTER TABLE sf_guard_user_group ADD PRIMARY KEY (`user_id`,`group_id`,`site_id`)",
    );
    foreach($commands as $command)
    {
      Doctrine_Manager::connection()->exec($command);
    }
    
    $group = new sfGuardGroup();
    $group->name = 'site_admin';
    $group->save();
    
    $permission = new sfGuardPermission();
    $permission->name = 'site_admin';
    $permission->save();
    
    $groupPermission = new sfGuardGroupPermission();
    $groupPermission->permission_id = $permission->id;
    $groupPermission->group_id = $group->id;
    $groupPermission->save();
    
    $userGroup = new sfGuardUserGroup();
    $userGroup->group_id = $group->id;
    $userGroup->user_id = 479; //nova
    $userGroup->site_id = SiteTable::getInstance()->createQuery('s')
                            ->select('s.id')->where('s.name = "dp2013"')->setHydrationMode(Doctrine::HYDRATE_SINGLE_SCALAR)->fetchOne();
    $userGroup->save();
  }

  public function down()
  {
  }
}
