<?php

/**
 * aButtonSlotTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class aButtonSlotTable extends PluginaButtonSlotTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object aButtonSlotTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aButtonSlot');
    }
}