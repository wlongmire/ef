<?php

class UpdateEventRequirements extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->changeColumn('event', 'cost', 'decimal', 8, array('scale' => 2));
    $this->changeColumn('event_occurance', 'end_time', 'time');
  }

  public function down()
  {
  }
}
