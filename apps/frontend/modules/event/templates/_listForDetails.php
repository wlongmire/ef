<?php $useAbsoluteUrls = (boolean)$sf_request['callback'] ?>
<?php if (count($events)): ?>
  <div class="events section inset">
    <div class="filter-header">
      <h4><?php echo isset($title) ? $title : 'Events' ?></h4>
      <?php if (isset($filter_url)): ?>
          <ul class="a-ui a-controls">
            <li>
              <a href="<?php echo $filter_url ?>" class="a-btn filter">View List</a>
            </li>
          </ul>
      <?php endif ?>
    </div>
    <ul class="info-list details clearfix">
      <?php foreach($events as $event): ?>
        <li>
          <span class="detail"><?php echo link_to($event['name'], 'event_show', $event, 
                                              array('class' => 'details', 'absolute' => $useAbsoluteUrls)) ?></span>
          <span class="info">
            <?php echo date('m/d/Y', aDate::normalize($event['start_date'])) ?>
          </span>
        </li>
      <?php endforeach ?>          
    </ul>
  </div>
<?php endif ?>