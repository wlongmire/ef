<?php if ($sf_user->hasCredential('admin')): ?>
  <?php extract(array(
      'tab' => 'venueAdmin' //if no tab is included, assume we're on eventAdmin
  ), EXTR_SKIP) ?>
  <?php slot('a-breadcrumb') ?>
  <?php $tabs = array(
    'venueAdmin' => array(
        'route' => 'venue_admin',
        'label' => 'Venues',
    ),
    'venueTypeAdmin' => array(
        'route' => 'venue_type_admin',
        'label' => 'Venue Types',
    )
  ) ?>
  <?php include_partial('default/admin_tabs', array(
      'tabs' => $tabs,
      'tab' => $tab
  )) ?>
  <?php end_slot() ?>
<?php endif ?>
