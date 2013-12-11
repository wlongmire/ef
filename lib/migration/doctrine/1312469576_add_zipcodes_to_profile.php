<?php

class AddZipcodesToProfile extends Doctrine_Migration_Base
{
  public function up()
  {
    foreach(array('home_zip_code', 'studio_zip_code') as $column)
    {
      $this->addColumn('profile', $column, 'string', 6);
    }
  }

  public function down()
  {
  }
}
