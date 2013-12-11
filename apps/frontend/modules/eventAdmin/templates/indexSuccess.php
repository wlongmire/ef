<?php use_helper('a', 'Date') ?>
<?php include_partial('eventAdmin/assets') ?>

<?php slot('a-page-header')?>
<div class="a-ui a-admin-header">
	<h3 class="a-admin-title"><?php echo $sf_user->hasCredential('admin') ? __('Event Admin') : __('Your Events') ?></h3>  
	<ul class="a-ui a-controls a-admin-controls">
    <?php if ($hasEvent): ?>
      <li class="a-admin-actions-filter">
        <?php echo a_js_button(a_('Filter'), array('icon','a-filters','alt','big'), 'a-admin-filters-open-button') ?>
      </li>
    <?php endif ?>  
    <?php include_partial('eventAdmin/list_actions', array('helper' => $helper)) ?>   
  </ul>
  <?php include_partial('eventAdmin/list_header', array('pager' => $pager)) ?>
</div>
<?php end_slot() ?>

<div class="a-ui a-admin-container <?php echo $sf_params->get('module') ?>" style="clear: left">
  <div class="a-admin-content main">

    <?php include_partial('eventAdmin/filters', array('form' => $filters, 'configuration' => $configuration, 'filtersActive' => $filtersActive)) ?>

    <?php include_partial('eventAdmin/flashes') ?>
    <?php if ($_site->hasCustomPaymentInstructions() && ($hasEvent || $sf_user->hasCredential('admin'))): ?>
      <?php include_partial('a/areaPaymentInstructions') ?>
    <?php endif ?>
    <?php if ($hasEvent): ?>
      <?php include_partial('eventAdmin/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>    
    <?php else: ?>
      <div class="notice">
        You haven't added any events yet.
        <?php echo link_to('Click here to add an event', 'event_admin_new') ?>.
      </div>
    <?php endif ?>
  </div>

  <div class="a-admin-footer">
    <?php include_partial('eventAdmin/list_footer', array('pager' => $pager)) ?>
  </div>
</div>

<?php a_js_call('apostrophe.aAdminEnableFilters()') ?>