<?php
/**
 * Originally copied from: Doctrine_Template_Sluggable
 * Modified to always check for unique values, fix bug regarding strtolower, and support updates of slugs
 */
class Doctrine_Template_WfSluggable extends Doctrine_Template
{
    /**
     * Array of Sluggable options
     *
     * @var string
     */
    protected $_options = array(
        'name'          =>  'slug',
        'alias'         =>  null,
        'type'          =>  'string',
        'length'        =>  255,
        'unique'        =>  true,
        'options'       =>  array(),
        'fields'        =>  array(),
        'uniqueBy'      =>  array(),
        'uniqueIndex'   =>  true,
        'update'        =>  false,
        'builder'       =>  array('Doctrine_Inflector', 'urlize'),
        'provider'      =>  null,
        'indexName'     =>  'sluggable'
    );

    /**
     * Set table definition for Sluggable behavior
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $name = $this->_options['name'];
        if ($this->_options['alias']) {
            $name .= ' as ' . $this->_options['alias'];
        }
        $this->hasColumn($name, $this->_options['type'], $this->_options['length'], $this->_options['options']);
        
        if ($this->_options['unique'] == true && $this->_options['uniqueIndex'] == true && ! empty($this->_options['fields'])) {
            $indexFields = array($this->_options['name']);
            $indexFields = array_merge($indexFields, $this->_options['uniqueBy']);
            $this->index($this->_options['indexName'], array('fields' => $indexFields,
                                                             'type' => 'unique'));
        }

        $this->addListener(new Doctrine_Template_Listener_WfSluggable($this->_options));
    }
}