<?php

/**
 * aVideoSlotTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class aVideoSlotTable extends PluginaVideoSlotTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object aVideoSlotTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aVideoSlot');
    }
}