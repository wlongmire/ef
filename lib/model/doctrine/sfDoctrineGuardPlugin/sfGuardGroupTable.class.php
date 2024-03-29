<?php

/**
 * sfGuardGroupTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardGroupTable extends PluginsfGuardGroupTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object sfGuardGroupTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardGroup');
    }
    
    public static function findGroupsForUserAndSite(sfGuardUser $user, Site $site)
    {
      return Doctrine_Query::create()
        ->from('sfGuardGroup g')
        ->innerJoin('g.Permissions p')
        ->innerJoin('g.sfGuardUserGroup gug')
        ->andWhere('gug.user_id = ? AND (gug.site_id = ? OR gug.site_id IS NULL OR gug.site_id = 0)', array($user->id, $site->id))
        ->execute();
    }
}