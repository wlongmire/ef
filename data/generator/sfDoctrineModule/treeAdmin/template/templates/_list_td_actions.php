<td class="actions">
    [?php $pk = $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]
    <input type="hidden" id="moved_node-[?php echo $pk ?]" name="newparent[[?php echo $pk ?]]" />
  <ul class="a-ui a-admin-td-actions">
<?php foreach ($this->configuration->getValue('list.object_actions') as $name => $params): ?>
<?php if ('_delete' == $name): ?>
    <?php echo $this->addCredentialCondition('[?php echo $helper->linkToDelete($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('_edit' == $name): ?>
    <?php echo $this->addCredentialCondition('[?php echo $helper->linkToEdit($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('newChild' == $name): ?>
    <?php echo $this->addCredentialCondition('[?php echo $helper->linkToNewChild($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>
<?php else: ?>
    <li class="a-admin-action-<?php echo $params['class_suffix'] ?>">
      <?php echo $this->addCredentialCondition($this->getLinkToAction($name, $params, true), $params) ?>
    </li>
<?php endif; ?>
<?php endforeach; ?>
  </ul>
</td>
