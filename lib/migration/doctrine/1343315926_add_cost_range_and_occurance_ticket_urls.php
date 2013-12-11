<?php

class AddCostRangeAndOccuranceTicketUrls extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection()->getDbh();
    $dbh->exec('ALTER TABLE event ADD COLUMN max_cost DECIMAL(8, 2)');
    $dbh->exec('ALTER TABLE event CHANGE cost min_cost DECIMAL(8, 2)');
    $dbh->exec('ALTER TABLE event_occurance ADD COLUMN ticket_url VARCHAR(255)');
  }

  public function down()
  {
  }
}
