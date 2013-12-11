<?php include_partial('site/localFiltersHeader', array(
    'model' => 'profile',
    'filters' => $filters,
    'profileIds' => $profileIds,
    'applied_filters' => $applied_filters,
    'base_filter_route' => 'profile_add_filter'
)) ?>
<?php slot('filter-results') ?>
  <?php include_partial('profile/list', array(
      'filters' => $filters,
      'pager' => $pager,
      'profiles' => $profiles,
      'applied_filters' => $applied_filters
  )) ?>
<?php end_slot() ?>
<?php if (!has_slot('filter-details')): ?>
  <?php foreach(array('profile') as $filter): ?>
    <?php if (isset($filters[$filter]) && $filters[$filter]): ?>
      <?php slot('filter-details') ?>
        <?php include_component($filter, 'details', array(
            $filter => $filters[$filter]
        )) ?>
      <?php end_slot() ?>
      <?php break ?>
    <?php endif ?>
  <?php endforeach ?>
<?php endif ?>
