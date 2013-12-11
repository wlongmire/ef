<?php if (isset($event['Venue'])): ?>
  <?php if ($sf_user->hasCredential('admin') || $sf_user->hasCredential('venue_manage')): ?>
    <?php echo link_to($event['Venue']['name'], 'venue_admin_edit', $event['Venue']) ?>
  <?php else: ?>
    <?php echo $event['Venue']['name'] ?>
  <?php endif ?>
<?php else: ?>
  <?php if ($event['suggested_venue_name']): ?>
    <?php echo $event['suggested_venue_name'] ?>
  <?php else: ?>
    <span class="empty">(no venue)</span>
  <?php endif ?>
<?php endif ?>

