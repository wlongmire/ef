<?php

/**
 * aPageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class aPageTable extends PluginaPageTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object aPageTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aPage');
    }
}