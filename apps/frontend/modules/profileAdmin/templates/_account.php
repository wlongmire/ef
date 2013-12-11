<?php if ($profile->relatedExists('User')): ?>
  <?php echo link_to($profile['User']['email_address'], 'user_admin_edit', $profile['User']) ?>
<?php endif ?>
