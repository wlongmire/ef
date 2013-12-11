<?php if (!$sf_user->isAuthenticated() || $sf_user->hasCredential('admin')): ?>
  <?php if ($sf_user->hasCredential('admin')): ?>
    <h6>Message for new or logged out visitors</h6>
  <?php endif ?>
  <?php include_partial('a/areaWelcome') ?>
<?php endif ?>
<?php if ($sf_user->isAuthenticated()): ?>
  <?php if ($sf_user->hasCredential('admin')): ?>
    <h6>Message for signed in visitors</h6>
  <?php endif ?>
  <?php include_partial('a/areaAuthenticatedWelcome') ?>
<?php endif ?>