<?php

/**
 * aBlogPostTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class aBlogPostTable extends PluginaBlogPostTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object aBlogPostTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('aBlogPost');
    }
}