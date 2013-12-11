<?php

class RemoveLocationRequirementFromProfile extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->changeColumn('profile', 'location_id', 'integer', 20);
  }

  public function down()
  {
  }
}
