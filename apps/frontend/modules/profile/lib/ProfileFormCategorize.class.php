<?php

/**
 * Description of ProfileUserForm
 *
 * @author jeremy
 */
class ProfileFormCategorize extends ProfileForm 
{
  public function configure()
  {
    $this->setOption('with_tags', true);
    
    $this->useFields(array('categories_list', 'disciplines_list'));    
    
    parent::configure();
       
    $this->getWidgetSchema()
      ->setLabel('disciplines_list', false)
      ->setLabel('tags', 'What describes you, the work you produce, or the materials you use?<br/><span class="help">Select as many as apply.</span>');
    
    if (!$this->object->is_group)
    {
      $catChoices = CategoryTable::getInstance()->findChoicesForIndividualCategory();
      $this->setWidget('categories_list', new myWidgetFormSelectCheckbox(array(
          'choices' => $catChoices,
          'helps' => array(
              9 => 'You sell soaps, jewelry etc. under a specific name.',
              7 => 'Curator, director, etc.',
              3 => 'Artist, writer, choreographer, etc.'
          ),
          'label' => 'What do you do?<br/><span class="help">Select as many as apply.</span>'
      )));
      $this->setValidator('categories_list', new sfValidatorChoice(array(
          'choices' => array_keys($catChoices),
          'multiple' => true
      )));          
    }
    else
    {
      $catChoices = CategoryTable::getInstance()->findSortedTreeChildrenOfGroupTree();
      $this->setWidget('categories_list', new myWidgetFormFilterTree(array(
        'tree' => $catChoices,
        'multiple' => true,
        'label' => 'What best describes your group or organization?<br/><span class="help">Select as many as apply.</span>'
      )));      
    }
    
    $this->getWidgetSchema()->setFormFormatterName('AAdmin');
  }
}
