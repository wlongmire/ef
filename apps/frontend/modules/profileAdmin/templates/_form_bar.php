<div class="a-admin-bar">
	<h2 class="a-admin-title you-are-here"><?php echo $title ?></h2>
  <?php if (isset($profile) && $profile->exists()): ?>
    <?php echo link_to('View this profile', 'profile_show', $profile) ?><br/>
  <?php endif ?>  
  <div class="return-link">
    <?php echo link_to('&laquo; Back to Profile Admin', 'profile_admin') ?>
  </div>
  <?php if (isset($profile) && !$profile->relatedExists('User')): ?>
    <div class="message light">
      This profile is not associated with an account yet. To create a new account associated with this profile, use the following URL:<br/><br/>
      <?php echo link_to(null, 'join', array('claim_token' => $profile->getClaimToken()), array('absolute' => true)) ?>
      <br/>
      <br/>
      Visiting the link will only work for unauthenticated users.
    </div>
  <?php endif ?>
</div>
<?php if (!isset($profile) || $profile->isNew()): ?>
  <?php a_slot('profile-edit-help', 'aRichText', array(
        'slug' => 'profile_admin'
    )) ?>      
<?php endif ?>