<div class="a-form-row submit">
  <ul class="a-ui a-controls">
    <li>
      <?php if ($form->isNew()): ?>
        <?php echo a_submit_button('Create Event', array('big')) ?>
      <?php else: ?>
        <?php echo a_submit_button('Update Event', array('big')) ?>
      <?php endif ?>
    </li>
    <li>
      <?php echo my_a_link_button('Cancel', 'event_admin', array(), array('icon', 'big', 'a-cancel')) ?>
    </li>
  </ul>
</div>