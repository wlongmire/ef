<?php

class ProperTags extends Doctrine_Migration_Base
{
  public function up()
  {
    $dbh = Doctrine_Manager::getInstance()->getCurrentConnection();
    foreach(array(
  'ALTER TABLE site ADD COLUMN location_id BIGINT',
  'CREATE TABLE site_discipline (site_id BIGINT, discipline_id BIGINT, PRIMARY KEY(site_id, discipline_id)) ENGINE = INNODB;',
  'CREATE TABLE site_tag_heading (site_id BIGINT, tag_heading_id BIGINT, PRIMARY KEY(site_id, tag_heading_id)) ENGINE = INNODB;',       
  'ALTER TABLE site ADD CONSTRAINT site_location_id_location_id FOREIGN KEY (location_id) REFERENCES location(id) ON UPDATE CASCADE ON DELETE SET NULL',
  'ALTER TABLE site_discipline ADD CONSTRAINT site_discipline_site_id_site_id FOREIGN KEY (site_id) REFERENCES site(id) ON UPDATE CASCADE ON DELETE CASCADE',
  'ALTER TABLE site_discipline ADD CONSTRAINT site_discipline_discipline_id_discipline_id FOREIGN KEY (discipline_id) REFERENCES discipline(id) ON UPDATE CASCADE ON DELETE CASCADE',
  'ALTER TABLE site_tag_heading ADD CONSTRAINT site_tag_heading_tag_heading_id_tag_heading_id FOREIGN KEY (tag_heading_id) REFERENCES tag_heading(id) ON UPDATE CASCADE ON DELETE CASCADE',
  'ALTER TABLE site_tag_heading ADD CONSTRAINT site_tag_heading_site_id_site_id FOREIGN KEY (site_id) REFERENCES site(id) ON UPDATE CASCADE ON DELETE CASCADE'        
    ) as $sql)
    {
      $dbh->exec($sql);
    }
    foreach(array('phlocal') as $siteName)
    {
      $site = SiteTable::getInstance()->findOneByName($siteName);
      $site->location_id = 1;
    }
    foreach(array(
        'theartblog' => null,
        'designphiladelphia' => TagTable::getInstance()->findById(203),
        'dp2013' => TagTable::getInstance()->findByName('DP13'),
        'fringearts' => TagTable::getInstance()->findByName('2013 Fringe')
    ) as $siteName => $tags)
    {
      $site = SiteTable::getInstance()->findOneByName($siteName);
      foreach($tags as $tag)
      {
        $tagging = new Tagging();
        $tagging->taggable_model = 'Site';
        $tagging->taggable_id = $site->id;
        $tagging->tag_id = $tag->id;
        $tagging->save();
      }
    }
    $sites = SiteTable::getInstance()->findAll();
    foreach($sites as $site)
    {
      $siteTagHeading = new SiteTagHeading();
      $siteTagHeading->site_id = $site->id;
      $siteTagHeading->tag_heading_id = TagHeadingTable::getInstance()->findOneByName('Audience')->id;
      $siteTagHeading->save();
    }
  }

  public function down()
  {
  }
}
