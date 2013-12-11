<?php $sf_response->setTitle('Events > ' . $event['name']) ?>
<?php if (!$sf_request->isXmlHttpRequest()): ?>
  <?php slot('a-breadcrumb') ?>
    <div class="return-breadcrumb">
      <?php echo link_to('&laquo; Return to event listings', 'event_index') ?>
    </div>
  <?php end_slot() ?>
<?php endif ?>
<?php include_component('event', 'details', array(
    'event' => $event
)) ?>
<?php if ($sf_request->isXmlHttpRequest() && $sf_user->hasCredential('admin')): ?>
  <?php include_partial('a/globalJavascripts') ?>
<?php endif ?>
