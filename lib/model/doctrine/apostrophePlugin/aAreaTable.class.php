<?php

/**
 * aAreaTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class aAreaTable extends PluginaAreaTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object aAreaTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aArea');
    }
}