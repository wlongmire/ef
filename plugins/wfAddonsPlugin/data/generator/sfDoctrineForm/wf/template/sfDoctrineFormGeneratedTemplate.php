[?php

/**
 * <?php echo $this->modelName ?> form base class.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->underscore($this->modelName) ?>
 * @author     ##AUTHOR_NAME##
 */
abstract class Base<?php echo $this->modelName ?>Form extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
<?php foreach ($this->getColumns() as $column): ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $this->getWidgetClassForColumn($column) ?>(<?php echo $this->getWidgetOptionsForColumn($column) ?>, <?php echo $this->getWidgetAttributesForColumn($column) ?>),
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new <?php echo $this->getManyToManyWidgetClass() ?>(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>')),
<?php endforeach; ?>
    ));

    $this->setValidators(array(
<?php foreach ($this->getColumns() as $column):
        $validatorClass = $this->getValidatorClassForColumn($column) ?>
      '<?php echo $column->getFieldName() ?>'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($column->getFieldName())) ?> => new <?php echo $validatorClass ?>(<?php echo $this->getValidatorOptionsForColumn($column) ?>, <?php echo $this->getValidatorMessages($validatorClass) ?>),
<?php if (!$column->isNotNull() && $this->requiresFileValidator($column)): ?>
      '<?php echo $column->getFieldName() ?>_delete'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - (strlen($column->getFieldName()) + 7)) ?> => new sfValidatorBoolean(array('required' => false)),
<?php endif ?>
<?php endforeach; ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>
      '<?php echo $this->underscore($relation['alias']) ?>_list'<?php echo str_repeat(' ', $this->getColumnNameMaxLength() - strlen($this->underscore($relation['alias']).'_list')) ?> => new <?php echo $this->getManyToManyValidatorClass() ?>(array('multiple' => true, 'model' => '<?php echo $relation['table']->getOption('name') ?>', 'required' => false)),
<?php endforeach; ?>
    ));

<?php if ($uniqueColumns = $this->getUniqueColumnNames()):
    $uniqueValidatorClass = $this->getUniqueValidatorClass(); ?>
    $this->validatorSchema->setPostValidator(
<?php if (count($uniqueColumns) > 1): ?>
      new sfValidatorAnd(array(
<?php foreach ($uniqueColumns as $uniqueColumn): ?>
        new <?php echo $uniqueValidatorClass ?>(<?php echo $this->getUniqueValidatorOptions($uniqueColumn) ?>, <?php echo $this->getValidatorMessages($uniqueValidatorClass) ?>),
<?php endforeach; ?>
      ))
<?php else: ?>
       new <?php echo $uniqueValidatorClass ?>(<?php echo $this->getUniqueValidatorOptions($uniqueColumns[0]) ?>, <?php echo $this->getValidatorMessages($uniqueValidatorClass) ?>)
<?php endif; ?>
    );

<?php endif; ?>
    $this->widgetSchema->setNameFormat('<?php echo $this->underscore($this->modelName) ?>[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

<?php $this->insertTemplatePartials('setup', true) ?>

    parent::setup();
  }

<?php echo $this->evalTemplate('sfDoctrineFormGeneratedCommonTemplate.php'); ?>
}