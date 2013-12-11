<?php $hide = $sf_request['hide'] ? array_flip(explode(',', $sf_request['hide'])) : array() ?>
<?php $useAbsoluteUrls = $sf_request['callback'] ?>
<tr class="<?php echo $class ?> list-item">
  <?php $item = $event['Picture'] ?>  
  <?php $profileNames = '' ?>
  <?php if (isset($event['Profiles'])): ?>
    <?php $profileCount = count($event['Profiles']) ?>
    <?php if ($profileCount): ?>
      <?php $count = 0 ?>
      <?php $visibleLimit = $profileCount <= 3 ? 3 : 2 ?>
      <?php $baseClasses = 'details profile' ?>
      <?php foreach($event['Profiles'] as $profile): ?>
        <?php ++$count ?>
        <?php if (!$item && $profile['Picture']): ?>
          <?php $item = $profile['Picture'] ?>
        <?php endif ?>
        <?php $classes = $baseClasses . ($count > $visibleLimit ? ' hidden' : '') ?>
        <?php $profileNames .= link_to($profile['name'], 'profile_show', Profile::parameterize($profile), 
                                  array('class' => $classes, 'query_string' => 'listings=event', 'absolute' => $useAbsoluteUrls)) ?>
        <?php if ($count < $profileCount): ?>
          <?php $profileNames .= '<span class="separator ' . ($count > $visibleLimit ? 'hidden' : '') . '">, </span>' ?>
        <?php endif ?>
      <?php endforeach ?>
      <?php if ($count > $visibleLimit): ?>
        <?php $profileNames .= '<a href="javascript:;" class="show-all">+ ' . ($count - $visibleLimit) . ' more</a>' ?>
      <?php endif ?>
    <?php endif ?>
  <?php endif ?>    
  <?php if ($showImage): ?>  
    <td class="image">
      <?php if ($item && $item['type'] == 'image'): ?>
        <a href="<?php echo url_for('event_show', Event::parameterize($event), $useAbsoluteUrls) ?>" class="details">
          <?php include_partial('aMedia/imageForList', array(
              'item' => $item
          )) ?>
        </a>
      <?php endif ?>
    </td>
  <?php endif ?>      
  <?php if ($showImage): ?>
    <td class="event standard">
      <?php $name = $highlight ? highlight_text($event['name'], $highlight, '<span class="highlight">\\1</span>') : $event['name'] ?>
      <?php echo link_to($name, 'event_show', Event::parameterize($event), array('class' => 'event details primary', 'absolute' => $useAbsoluteUrls)) ?>          
      <br/>
      <?php echo $profileNames ?>
    </td>    
    <?php if (!isset($hide['location']) || !isset($hide['discipline']) || !isset($hide['venue'])): ?>        
      <td class="location standard">
        <?php if (!isset($hide['discipline'])): ?>    
          <?php if (isset($event['Disciplines'])): ?>
            <?php echo implode(', ', wfToolkit::arrayPluck($event['Disciplines'], 'name')) ?>
            <br/>
          <?php endif ?>           
        <?php endif ?>        
        <?php if (isset($event['Venue']) && !isset($hide['venue'])): ?> 
          <?php echo link_to($event['Venue']['name'], 'venue_show', Venue::parameterize($event['Venue']),
                        array('class' => 'venue details', 'absolute' => $useAbsoluteUrls)) ?>
          <br/>
        <?php endif ?>
        <?php if (isset($event['Venue']) && !isset($hide['location'])): ?>           
          <?php echo $event['Venue']['Location']['name'] ?>    
        <?php endif ?>
      </td>
    <?php endif ?>
  <?php else: ?>
    <td class="primary event standard">
      <?php echo link_to($event['name'], 'event_show', Event::parameterize($event),
                    array('class' => 'event details', 'absolute' => $useAbsoluteUrls)) ?>    
    </td>
    <td class="profile standard">
      <?php echo $profileNames ?>
    </td>    
   <?php if (isset($event['Venue']) && (!isset($hide['location']) || !isset($hide['venue']))): ?>
      <td class="location standard">
        <?php if (!isset($hide['venue'])): ?>
          <?php echo link_to($event['Venue']['name'], 'venue_show', Venue::parameterize($event['Venue']), 
                          array('class' => 'venue details', 'absolute' => $useAbsoluteUrls)) ?>
          <br/>
        <?php endif ?>
        <?php if (!isset($hide['location'])): ?>
          <?php echo $event['Venue']['Location']['name'] ?>    
        <?php endif ?>
      </td>
    <?php endif ?>
    <?php if (!isset($hide['discipline'])): ?>    
      <td class="discipline standard">
        <?php if (isset($event['Disciplines'])): ?>
          <?php echo implode(', ', wfToolkit::arrayPluck($event['Disciplines'], 'name')) ?>
        <?php endif ?>
      </td>  
    <?php endif ?>    
  <?php endif ?>
  <?php if (!isset($hide['time_and_cost'])): ?>    
    <?php if ($time_or_cost): ?>
      <td class="time standard last">
        <div class="time"><?php echo $event['start_time'] ? aDate::time($event['start_time']) : '' ?></div>
        <div class="event-cost"><?php echo event_cost($event) ?></div>
      </td>
    <?php endif ?>  
  <?php endif ?>
</tr>
