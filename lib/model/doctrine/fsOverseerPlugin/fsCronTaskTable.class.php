<?php

/**
 * fsCronTaskTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class fsCronTaskTable extends PluginfsCronTaskTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object fsCronTaskTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('fsCronTask');
    }
}