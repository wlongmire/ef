<?php if ($profile->relatedExists('Location')): ?>
  <?php echo link_to($profile['Location'], 'location_admin_edit', $profile['Location']) ?>
<?php endif ?>
