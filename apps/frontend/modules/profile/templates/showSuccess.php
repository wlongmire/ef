<?php $sf_response->setTitle('Profile > ' . $profile['name']) ?>
<?php if (!$sf_request->isXmlHttpRequest()): ?>
  <?php slot('a-breadcrumb') ?>
    <div class="return-breadcrumb">
      <?php echo link_to('&laquo; Return to profile listings', 'profile_index') ?>
    </div>
  <?php end_slot() ?>
<?php endif ?>
<?php include_component('profile', 'details', array(
    'profile' => $profile
)) ?>
<?php if ($sf_request->isXmlHttpRequest() && $sf_user->hasCredential('admin')): ?>
  <?php include_partial('a/globalJavascripts') ?>
<?php endif ?>
