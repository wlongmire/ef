<?php

/**
 * BaseEventProfile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $event_id
 * @property integer $profile_id
 * @property Event $Event
 * @property Profile $Profile
 * 
 * @method integer      getEventId()    Returns the current record's "event_id" value
 * @method integer      getProfileId()  Returns the current record's "profile_id" value
 * @method Event        getEvent()      Returns the current record's "Event" value
 * @method Profile      getProfile()    Returns the current record's "Profile" value
 * @method EventProfile setEventId()    Sets the current record's "event_id" value
 * @method EventProfile setProfileId()  Sets the current record's "profile_id" value
 * @method EventProfile setEvent()      Sets the current record's "Event" value
 * @method EventProfile setProfile()    Sets the current record's "Profile" value
 * 
 * @package    eventsfilter
 * @subpackage model
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseEventProfile extends wfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('event_profile');
        $this->hasColumn('event_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('profile_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Event', array(
             'local' => 'event_id',
             'foreign' => 'id',
             'onDelete' => 'cascade',
             'onUpdate' => 'cascade'));

        $this->hasOne('Profile', array(
             'local' => 'profile_id',
             'foreign' => 'id',
             'onDelete' => 'cascade',
             'onUpdate' => 'cascade'));
    }
}