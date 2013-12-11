<?php if ($sf_user->hasCredential('admin')): ?>
  <?php extract(array(
      'tab' => 'eventAdmin' //if no tab is included, assume we're on eventAdmin
  ), EXTR_SKIP) ?>
  <?php slot('a-breadcrumb') ?>
  <?php $tabs = array(
    'eventAdmin' => array(
        'route' => 'event_admin',
        'label' => 'Events',
    ),
    'eventTypeAdmin' => array(
        'route' => 'event_type_admin',
        'label' => 'Event Types',
    )
  ) ?>
  <?php include_partial('default/admin_tabs', array(
      'tabs' => $tabs,
      'tab' => $tab
  )) ?>
  <?php end_slot() ?>
<?php endif ?>
