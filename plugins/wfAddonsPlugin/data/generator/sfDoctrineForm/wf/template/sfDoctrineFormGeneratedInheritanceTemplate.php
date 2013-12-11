[?php

/**
 * <?php echo $this->modelName ?> form base class.
 *
 * @method <?php echo $this->modelName ?> getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 */
abstract class Base<?php echo $this->modelName ?>Form extends <?php echo $this->getFormClassToExtend() . "\n" ?>
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

<?php foreach ($this->getColumns() as $column):
    $validatorClass = $this->getValidatorClassForColumn($column) ?>
    $this->widgetSchema   ['<?php echo $column->getFieldName() ?>'] = new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>, <?php echo $this->getWidgetAttributesForColumn($column) ?>);
    $this->validatorSchema['<?php echo $column->getFieldName() ?>'] = new <?php echo $validatorClass ?>(<?php echo $this->getValidatorOptionsForColumn($column) ?>, <?php echo $this->getValidatorMessages($validatorClass) ?>);
<?php if (!$column->isNotNull() && $this->requiresFileValidator($column)): ?>
    $this->validatorSchema['<?php echo $column->getFieldName() ?>_delete'] = new sfValidatorBoolean(array('required' => false));
<?php endif ?>
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
    $this->widgetSchema   ['<?php echo $this->underscore($relation['alias']) ?>_list'] = new <?php echo $this->getManyToManyWidgetClass() ?>(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>'));
    $this->validatorSchema['<?php echo $this->underscore($relation['alias']) ?>_list'] = new <?php echo $this->getManyToManyValidatorClass() ?>(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>', 'required' => false));
<?php endforeach; ?>

    $this->widgetSchema->setNameFormat('<?php echo $this->underscore($this->modelName) ?>[%s]');

<?php $this->insertTemplatePartials('setup', true) ?>
  }

<?php echo $this->evalTemplate('sfDoctrineFormGeneratedCommonTemplate.php'); ?>
}
