<?php

/**
 * BaseSiteTagHeading
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $site_id
 * @property integer $tag_heading_id
 * @property Site $Site
 * @property TagHeading $TagHeading
 * 
 * @method integer        getSiteId()         Returns the current record's "site_id" value
 * @method integer        getTagHeadingId()   Returns the current record's "tag_heading_id" value
 * @method Site           getSite()           Returns the current record's "Site" value
 * @method TagHeading     getTagHeading()     Returns the current record's "TagHeading" value
 * @method SiteTagHeading setSiteId()         Sets the current record's "site_id" value
 * @method SiteTagHeading setTagHeadingId()   Sets the current record's "tag_heading_id" value
 * @method SiteTagHeading setSite()           Sets the current record's "Site" value
 * @method SiteTagHeading setTagHeading()     Sets the current record's "TagHeading" value
 * 
 * @package    eventsfilter
 * @subpackage model
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSiteTagHeading extends wfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('site_tag_heading');
        $this->hasColumn('site_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('tag_heading_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Site', array(
             'local' => 'site_id',
             'foreign' => 'id',
             'onDelete' => 'cascade',
             'onUpdate' => 'cascade'));

        $this->hasOne('TagHeading', array(
             'local' => 'tag_heading_id',
             'foreign' => 'id',
             'onDelete' => 'cascade',
             'onUpdate' => 'cascade'));
    }
}