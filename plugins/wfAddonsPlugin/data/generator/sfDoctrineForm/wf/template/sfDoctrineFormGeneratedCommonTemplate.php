  public function getModelName()
  {
    return '<?php echo $this->modelName ?>';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
<?php foreach ($this->getManyToManyRelations() as $relation): ?>

    if (isset($this->widgetSchema['<?php echo $this->underscore($relation['alias']) ?>_list']))
    {
      $this->setDefault('<?php echo $this->underscore($relation['alias']) ?>_list', $this->object-><?php echo $relation['alias']; ?>->getPrimaryKeys());
    }
<?php endforeach; ?>

<?php $this->insertTemplatePartials('updateDefaultsFromObject') ?>
  }

  protected function doUpdateObject($values)
  {
<?php $this->insertTemplatePartials('doUpdateObject') ?>
    parent::doUpdateObject($values);
  }

  protected function doSave($con = null)
  {
<?php if ($this->getManyToManyRelations()): ?>
    $this->saveManyToMany($con);
<?php endif ?>
<?php $this->insertTemplatePartials('doSave') ?>
    parent::doSave($con);
  }

<?php if ($manyToManyRelations = $this->getManyToManyRelations()): ?>
  public function saveManyToMany($con = null, $values = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
    }
    if ($values === null)
    {
      $values = $this->getValues();
    }
<?php foreach ($manyToManyRelations as $relation): ?>
    if (isset($this->widgetSchema['<?php echo $this->underscore($relation['alias']) ?>_list']))
    {
      $this->save<?php echo $relation['alias'] ?>List($con, isset($values['<?php echo $this->underscore($relation['alias']) ?>_list']) ? $values['<?php echo $this->underscore($relation['alias']) ?>_list'] : null);
    }
<?php endforeach; ?>
  }
<?php endif ?>
<?php foreach ($this->getManyToManyRelations() as $relation): ?>

  protected function save<?php echo $relation['alias'] ?>List(Doctrine_Connection $con, $values = null)
  {
    $existing = $this->object-><?php echo $relation['alias']; ?>->getPrimaryKeys();

    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('<?php echo $relation['alias'] ?>', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('<?php echo $relation['alias'] ?>', array_values($link));
    }
  }
<?php endforeach; ?>

<?php $this->insertTemplatePartials() ?>