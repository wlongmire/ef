<?php use_helper('a', 'Date') ?>
<?php include_partial('eventAdmin/assets') ?>

<div class="a-ui a-admin-container <?php echo $sf_params->get('module') ?>">
  <?php include_partial('eventAdmin/form_bar', array('event' => $event, 'title' => __('Edit Event', array(), 'messages'))) ?>

  <div class="a-admin-content main">
	  <?php include_partial('eventAdmin/flashes') ?>
 		<?php include_partial('eventAdmin/form', array('event' => $event, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div class="a-admin-footer">
 		<?php include_partial('eventAdmin/form_footer', array('event' => $event, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

</div>
