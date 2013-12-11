<h1 class="page-title">Your Profile</h1>
<?php $sf_response->setTitle('Manage Profile') ?>
<div class="my-tabs big left">
  <ul>
    <?php $profileTabs = array('basic info', 'work') ?>
    <?php if ($user->relatedExists('Profile') && $user->Profile->is_group): ?>
      <?php $profileTabs[] = 'group members' ?>
    <?php endif ?>
    <?php foreach($profileTabs as $profileTab): ?>
      <li <?php echo_if ($tab == $profileTab, 'class="selected"') ?>>
        <?php echo link_to(ucwords($profileTab), 'profile_edit', array('tab' => $profileTab)) ?>
      </li>    
    <?php endforeach ?>
    <li <?php echo_if ($tab == 'account', 'class="selected"') ?>>
      <?php if ($user->exists()): ?>
        <?php echo link_to('Account', 'user_edit') ?>
      <?php else: ?>
        <span>Account</span>
      <?php endif ?>
    </li>      
  </ul>
</div>