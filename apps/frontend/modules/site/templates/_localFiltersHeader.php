<?php if (!$sf_request['iframe']): ?>
  <?php slot('local-nav', get_component('site', 'navigation')) ?>
<?php endif ?>
<?php slot('filter-results-header') ?>
  <?php include_partial($model . '/viewingMessage', array(
      'filters' => $filters
  )) ?>
  <?php if (sfConfig::get('enabled_local_filters')): ?>
    <?php if(sfConfig::get('has_viewing_message')): ?>
      <hr class="a-hr clearfix"/>
    <?php endif ?>
    <div class="local-filters filter-scope" id="local-filters">
      <?php $searchFilterCandidates = array('name', 'date') ?>
      <?php $searchFilters = array_intersect(sfConfig::get('enabled_local_filters'), $searchFilterCandidates) ?>
      <?php $refineFilters = array_diff(sfConfig::get('enabled_local_filters'), $searchFilterCandidates) ?>
      <?php if ($searchFilters): ?>
        <span class="label">Search:</span>
        <ul class="filter-controls clearfix">
        <?php $row = 0 ?>
        <?php foreach($searchFilters as $filter): ?>
          <li class="<?php echo parity(++$row) ?> <?php echo_if($row > 2, 'multi-row') ?>">      
            <?php if ($filter == 'date'): ?>
              <?php include_component('event', 'dateFilter', array(
                  'filters' => $filters
              )) ?>
            <?php elseif ($filter == 'name'): ?>
              <?php include_component($model, 'searchFilter', array(
                  'filters' => $filters
              )) ?>
            <?php endif ?>
          </li>
        <?php endforeach ?>
        </ul>
      <?php endif ?>
      <?php if ($refineFilters): ?>
        <?php if ($searchFilters): ?>
          <hr class="a-hr clearfix"/>
        <?php endif ?>
        <span class="label">
          Filter:
        </span>
        <?php if (false): //debugging can remove ?>
          <?php foreach($filters as $key => $value): ?>
            <?php echo $key ?>: <?php echo is_object($value) ? get_class($value) : $value ?><br/>
          <?php endforeach ?>
        <?php endif ?>
        <ul class="filter-controls clearfix">
          <?php $row = 0 ?>
          <?php foreach($refineFilters as $filter): ?>
            <li class="<?php echo parity(++$row) ?> <?php echo_if($row > 2, 'multi-row') ?>">
              <?php include_component('site', 'singleFilter', array(
                  'filter' => $filter,
                  'filters' => $filters,
                  'eventIds' => isset($eventIds) ? $eventIds : array(),
                  'profileIds' => isset($profileIds) ? $profileIds : array(),
                  'active' => isset($filters[$filter]) ? $filters[$filter] : null,
                  'base_filter_route' => $base_filter_route
              )) ?>            
            </li>
          <?php endforeach ?> 
        </ul>
        <div class="filter-reset hide clearfix">
          <?php echo my_a_js_button('Reset filters', array('alt', 'a-ui', 'clear-local-filters')) ?>
        </div>
      <?php endif ?>
    </div>
    <hr class="a-hr clearfix"/>       
  <?php endif ?>
<?php end_slot() ?>