<?php include_partial('site/localFiltersHeader', array(
    'model' => 'event',
    'filters' => $filters,
    'eventIds' => $eventIds,
    'applied_filters' => $applied_filters,
    'base_filter_route' => 'event_add_filter'
)) ?>
<?php slot('filter-results') ?>
  <?php include_partial('event/list', array(
      'filters' => $filters,
      'events' => $events,
      'time_or_cost' => $time_or_cost,
      'applied_filters' => $applied_filters
  )) ?>
<?php end_slot() ?>
<?php if (!has_slot('filter-details')): ?>
  <?php foreach(array('event', 'profile', 'venue') as $filter): ?>
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