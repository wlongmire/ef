<?php if ($sf_user->hasCredential('admin')): ?>
  <?php extract(array(
      'tab' => 'profileAdmin' //if no tab is included, assume we're on eventAdmin
  ), EXTR_SKIP) ?>
  <?php slot('a-breadcrumb') ?>
  <?php $tabs = array(
    'profileAdmin' => array(
        'route' => 'profile_admin',
        'label' => 'Profiles',
    ),
    'categoryAdmin' => array(
        'route' => 'category_admin',
        'label' => 'Profile Categories',
    )
  ) ?>
  <?php include_partial('default/admin_tabs', array(
      'tabs' => $tabs,
      'tab' => $tab
  )) ?>
  <?php end_slot() ?>
<?php endif ?>