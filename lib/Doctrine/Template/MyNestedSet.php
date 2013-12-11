<?php
/*
 * Extended to set our own tree implementation
 */
class Doctrine_Template_MyNestedSet extends Doctrine_Template_NestedSet
{
  /**
   * Set up NestedSet template
   *
   * @return void
   */
  public function setUp()
  {
    parent::setUp();
    $this->_table->setOption('treeImpl', 'MyNestedSet');
  }

  public function getIndentedName()
  {
    $object = $this->getInvoker();
    return str_repeat('-&nbsp;', $object->level) . $object->name;
  }
  
  /**
   * Set table definition for MyNestedSet behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    parent::setTableDefinition();
    $this->hasOne(get_class($this->getInvoker()) . ' as Root', array(
         'local' => 'root_id',
         'foreign' => 'id',
         'onDelete' => 'cascade',
         'onUpdate' => 'cascade'
    ));
  }
}