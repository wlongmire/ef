<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
<?php echo $this->addCredentialCondition(sprintf(<<<EOF
<td class="a-admin-%s %s [?php echo '%s' == \$configuration->getTreeColumn() ? 'tree-column' : '' ?]"
  ><span class="[?php echo $%s->getNode()->isLeaf() ? 'file' : 'folder' ?] tree-icon">[?php echo %s ?]</span></td>

EOF
, strtolower($field->getType()), $name, $name, $this->getSingularName(),
  $this->renderField($field)), $field->getConfig()) ?>
<?php endforeach; ?>
