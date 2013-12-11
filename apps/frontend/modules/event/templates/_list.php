<?php $hide = $sf_request['hide'] ? array_flip(explode(',', $sf_request['hide'])) : array() ?>
<?php use_helper('Text') ?>
<div id="filter-response" class="filter-response filter-list">
  <?php if (count($events)): ?>
    <?php $highlight = isset($filters['name']) ? $filters['name'] : false ?>
    <?php if ($sf_request['callback']): ?>
      <table class="filter-data event">
    <?php endif ?>
    <?php foreach($events as $date => $eventsOnDate): ?>
      <?php $date = new DateTime($date) ?>
      <?php if (!isset($hide['header']) && (!$sf_request['sort'] || $sf_request['sort'] == 'date')): ?>
        <?php if ($sf_request['callback']): ?>
          <tr class="date"><td colspan="100">
        <?php endif ?>
        <h4 class="header date"><span class="text"><?php echo event_date($date) ?></span></h4>
        <?php if ($sf_request['callback']): ?>
          </td></tr>
        <?php endif ?>
      <?php endif ?>
      <?php $row = 0 ?>
      <?php $showImages = isset($sf_request['images']) ? //if in an iframe, arg must take precedence
              $sf_request['images'] != 'no' :
              $_site->alwaysShowImages() || $sf_user->getAttribute('event.show_images') ?>
      <?php if (!$sf_request['callback']): ?>
        <table class="filter-data event <?php echo $_site->useFixedColumns() && (!$sf_request['iframe'] || $sf_request['view'] == 'full') ? 'fixed-columns' : '' ?>
          <?php echo $time_or_cost ? 'with-time-or-cost' : 'no-time-or-cost' ?> <?php echo $showImages ? 'with-images' : 'no-images' ?>">               
      <?php endif ?>
        <?php foreach($eventsOnDate as $event): ?>
          <?php include_partial('event/listItem', array(
              'event' => $event,
              'class' => parity(++$row),
              'highlight' => $highlight,
              'showImage' => $showImages,              
              'time_or_cost' => $time_or_cost
          )) ?>
        <?php endforeach ?>
      <?php if (!$sf_request['callback']): ?>
        </table>
      <?php endif ?>
    <?php endforeach ?>
    <?php if ($sf_request['callback']): ?>
      </table>
    <?php endif ?>
    <?php if ($filters['paginate'] && !$sf_request['callback']): ?>      
      <?php $tomorrow = clone $filters['end_date'] ?>
      <?php $tomorrow->modify('+1 day') ?>      
      <div class="pagination a-ui clearfix">
        <?php echo a_link_button('Show More Events', 'event_paginate', array('date' => $tomorrow->format('Y-m-d')), array('paginate', 'big')) ?>
      </div>
    <?php endif ?>    
  <?php else: ?>
    <div class="no-results">
      There are no events listed at this time.
    </div>
  <?php endif ?>
</div>