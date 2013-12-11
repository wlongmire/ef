<td class="batch-actions first">
  <?php if ((!method_exists($event, 'userHasPrivilege')) || $event->userHasPrivilege('edit')): ?>
  <input type="checkbox" name="ids[]" value="<?php echo $event->getPrimaryKey() ?>" class="a-admin-batch-checkbox a-checkbox" />
  <?php endif; ?>
</td>
