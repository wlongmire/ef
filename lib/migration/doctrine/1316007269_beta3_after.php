<?php

class Beta3After extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->createForeignKey('event', 'event_media_item_id_a_media_item_id', array(
      'name' => 'event_media_item_id_a_media_item_id',
      'local' => 'media_item_id',
      'foreign' => 'id',
      'foreignTable' => 'a_media_item',
      'onUpdate' => 'CASCADE',
      'onDelete' => 'SET NULL',
    ));    
  }

  public function down()
  {
  }
}
