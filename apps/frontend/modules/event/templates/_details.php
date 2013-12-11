<?php if (!$sf_request['callback']): ?>
  <?php include_partial('default/adDetails', array(
      'attribute' => 'event.filters'
  )) ?>
<?php endif ?>
<?php $useAbsoluteUrls = (boolean)$sf_request['callback'] ?>
<div class="details event">
  <div class="section">
    <?php if (!$sf_request['callback']): ?>
      <h2><?php echo $event['name'] ?></h2>
    <?php else: ?>
      <a href="javascript:;" class="close-details" style="float: right">X</a>
      <h4><?php echo $event['name'] ?></h4>
    <?php endif ?>
     <div class="cost-and-tickets">
      <?php echo event_cost($event) ?>
      <?php if ($event['ticket_url']): ?>
        <a href="<?php echo $event['ticket_url'] ?>" target="_blank">Get Tickets</a>
      <?php endif ?>  
    </div>
    <?php foreach($event['Disciplines'] as $discipline): ?>
      <?php if ($discipline): ?>
        <?php include_component('detail', 'relationTree', array(
            'record' => $discipline,
            'class' => 'disciplines'
        )) ?>
      <?php endif ?>   
    <?php endforeach ?>
    <?php if (count($tags)): ?>
      <div class="tags clearfix">
        <span class="plain icon alt a-tags"><span class="icon"></span></span>
        <?php echo implode(', ', $tags) ?>
      </div>
    <?php endif ?>
  </div>
  <div class="section">
    <?php if (!$event->relatedExists('EventRecurrance')): ?>
      <ul class="info-list dates clearfix">
        <?php foreach($event->EventOccurances as $eventOccurance): ?>
          <li>
            <?php $hasTime = (boolean)$eventOccurance['start_time'] ?>
            <span class="detail <?php echo_if(!$hasTime, 'no-info') ?>">
              <?php echo date('M d', aDate::normalize($eventOccurance['start_date'])) ?>
              <?php if ($eventOccurance['start_date'] != $eventOccurance['end_date']): ?>
               <span class="separator">-</span> <?php echo date('M d, Y', aDate::normalize($eventOccurance['end_date'])) ?>
              <?php endif ?>
            </span>
            <?php if ($eventOccurance['ticket_url']): ?>
              <span class="buy"><a href="<?php echo $eventOccurance['ticket_url'] ?>" target="_blank">Buy</a></span>
            <?php endif ?>            
            <?php if ($hasTime): ?>
              <span class="info">
                <?php echo aDate::time($eventOccurance['start_time']) ?>
                <?php if ($eventOccurance['end_time']): ?>
                  -
                  <?php echo aDate::time($eventOccurance['end_time']) ?>
                <?php endif ?> 
              </span>
            <?php endif ?>         
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </div>
  <?php if ($event->media_item_id): ?>
    <div class="section">
      <?php include_partial('aMedia/image', array(
        'item' => $event->Picture,
        'variant' => 'detail'
      )) ?>
    </div>
  <?php endif ?>  
  <?php if ($event->relatedExists('Venue')): ?>
    <div class="section">
      <div class="media-description">
        <?php echo link_to($event['Venue']['name'], 'venue_show', $event['Venue'], array('class' => 'details')) ?><br/>
        <?php echo $event->Venue->getAddress(array('single_line' => false)) ?>
        <br/>
        <a href="<?php echo map_url($event->Venue->getAddress(array('single_line' => true))) ?>" target="_blank">View Map</a>
      </div>
    </div>
  <?php endif ?>
  <?php if ($event['blurb']): ?>
    <div class="blurb section">
      <?php echo expandable_blurb($event['blurb']) ?>
    </div>
  <?php endif ?>
  <div class="profiles section">
    <ul class="info-list details clearfix">
      <?php if ($event->relatedExists('Venue')): ?>
        <li>
          <span class="detail"><?php echo link_to($event['Venue']['name'], 'venue_show', $event['Venue'], array('class' => 'details')) ?></span>
          <span class="info">Venue</span>
        </li>
      <?php endif ?>
      <?php foreach($event['Profiles'] as $profile): ?>
        <li>
          <span class="detail"><?php echo link_to($profile['name'], 'profile_show', $profile, array('class' => 'details')) ?></span>
          <?php if (count($profile['Categories'])): ?>
            <span class="info"><?php echo $profile['Categories'][0]['name'] ?></span></li>      
          <?php endif ?>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
  <?php if ($event['url']): ?>
    <div class="website <?php echo_if($event['ticket_url'], 'slot') ?>">
      <a href="<?php echo aUrl::addProtocol($event['url']) ?>" title="<?php echo $event['url'] ?>" target="_blank">View Website</a>
    </div>
  <?php endif ?>
  <?php /*include_partial('event/share', array(
      'event' => $event
  ))*/ ?>
</div>