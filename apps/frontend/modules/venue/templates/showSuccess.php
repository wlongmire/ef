<?php $sf_response->setTitle('Venues > ' . $venue['name']) ?>
<?php if (!$sf_request->isXmlHttpRequest()): ?>
  <?php slot('a-breadcrumb') ?>
    <div class="return-breadcrumb">
      <?php echo link_to('&laquo; Return to event listings', 'event_index') ?>
    </div>
  <?php end_slot() ?>
<?php endif ?>
<?php include_component('venue', 'details', array(
    'venue' => $venue
)) ?>
<?php if ($sf_request->isXmlHttpRequest() && $sf_user->hasCredential('admin')): ?>
  <?php include_partial('a/globalJavascripts') ?>
<?php endif ?>
