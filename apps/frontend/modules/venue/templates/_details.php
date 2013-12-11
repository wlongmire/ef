<?php if (!$sf_request['callback']): ?>
  <?php include_partial('default/adDetails', array(
      'attribute' => 'event.filters'
  )) ?>
<?php endif ?>
<?php $useAbsoluteUrls = (boolean)$sf_request['callback'] ?>
<div class="details event">
  <div class="section">
    <?php if (!$sf_request['callback']): ?>
      <h2><?php echo $venue['name'] ?></h2>
    <?php else: ?>
      <a href="javascript:;" class="close-details" style="float: right">X</a>
      <h4><?php echo $venue['name'] ?></h4>
    <?php endif ?>    
  </div>
  <?php include_partial('detail/map', array(
      'addressable' => $venue
  )) ?>
  <?php if ($venue['blurb']): ?>
    <div class="blurb section">
      <?php echo expandable_blurb($venue['blurb']) ?>
    </div>
  <?php endif ?>
  <?php include_partial('event/listForDetails', array(
      'events' => $events,
      'filter_url' => url_for('event_toggle_filter', 
                               array('filter' => 'venue', 'value' => $venue['id'], 'label' => myTools::urlify($venue['name'])))
  )) ?>  
  <?php if ($venue['url']): ?>
    <div class="website">
      <a href="<?php echo aUrl::addProtocol($venue['url']) ?>" title="<?php echo $venue['url'] ?>" target="_blank">View Website</a>
    </div>
  <?php endif ?>
</div>