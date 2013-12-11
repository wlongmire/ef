<?php extract(array(
    'tab' => 'tagAdmin' //if no tab is included, assume we're on tagAdmin
), EXTR_SKIP) ?>
<?php slot('a-breadcrumb') ?>
<?php $tabs = array(
  'tagAdmin' => array(
      'route' => 'tag_admin',
      'label' => 'Tags',
  ),
  'tagHeadingAdmin' => array(
      'route' => 'tag_heading_admin',
      'label' => 'Tag Headings',
  )
) ?>
<?php include_partial('default/admin_tabs', array(
    'tabs' => $tabs,
    'tab' => $tab
)) ?>
<?php end_slot() ?>