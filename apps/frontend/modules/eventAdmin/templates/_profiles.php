<?php $links = array() ?>
<?php foreach($event['Profiles'] as $profile): ?>
  <?php if ($sf_user->hasCredential('admin')): ?>
    <?php $links[] = link_to($profile['name'], 'profile_admin_edit', $profile) ?>
  <?php else: ?>
    <?php $links[] = $profile['name'] ?>
  <?php endif ?>
<?php endforeach ?>
<?php echo implode('<br/>', $links) ?>
