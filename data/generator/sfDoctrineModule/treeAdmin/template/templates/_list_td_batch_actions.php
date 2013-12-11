<td class="batch-actions first">
  [?php $pk = $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]
  [?php if ((!method_exists($<?php echo $this->getSingularName() ?>, 'userHasPrivilege')) || $<?php echo $this->getSingularName() ?>->userHasPrivilege('edit')): ?]
  <input type="checkbox" name="ids[]" value="[?php echo $pk ?]" class="a-admin-batch-checkbox a-checkbox" />
  [?php endif; ?]
</td>
