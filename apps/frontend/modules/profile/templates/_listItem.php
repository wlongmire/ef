<?php $hide = $sf_request['hide'] ? array_flip(explode(',', $sf_request['hide'])) : array() ?>
<tr class="<?php echo $class ?>">
  <?php if ($showImage): ?>  
    <td class="image">
      <?php if ($profile['Picture']): ?>
        <a href="<?php echo url_for('profile_show', Profile::parameterize($profile)) ?>" class="details">
          <?php include_partial('aMedia/imageForList', array(
              'item' => $profile['Picture']
          )) ?>
        </a>      
      <?php endif ?>
    </td>
  <?php endif ?>    
  <td class="profile standard primary">
    <?php $classes = 'details ' . ($profile['has_group_member'] ? 'starred' : '') ?>
    <?php $name = $highlight ? highlight_text($profile['name'], $highlight, '<span class="highlight">\\1</span>') : $profile['name'] ?>
    <?php echo link_to($name, 'profile_show', Profile::parameterize($profile), array('class' => $classes)) ?>    
  </td>  
  <?php if ($showImage): ?>
    <td class="standard last">
  <?php endif ?>
  <?php if (!isset($hide['category'])): ?>      
    <?php if (!$showImage): ?>
      <td class="category standard">
        <?php if (isset($profile['Categories']) && count($profile['Categories'])): ?>
          <div class="filter-data">
            <?php echo $profile['Categories'][0]['name'] ?>
          </div>
        <?php endif ?>        
      </td>      
    <?php else: ?>
      <div class="filter-data">
        <?php echo str_replace(',', ', ', $profile['categories_all']) //fucking doctrine hates queries ?>
      </div>            
    <?php endif ?>
  <?php endif ?>
  <?php if (!isset($hide['discipline'])): ?>      
    <?php if (!$showImage): ?>
      <td class="discipline standard">
        <?php if (isset($profile['Disciplines']) && count($profile['Disciplines'])): ?>
          <div class="filter-data">
            <?php echo $profile['Disciplines'][0]['name'] ?>
          </div>
        <?php endif ?>
      </td>
    <?php else: ?>
      <div class="filter-data">
        <?php echo str_replace(',', ', ', $profile['disciplines_all']) //fucking doctrine hates queries ?>
      </div>      
    <?php endif ?>
  <?php endif ?>
  <?php if (!isset($hide['location'])): ?>        
    <?php if (!$showImage): ?><td class="location standard"><?php endif ?>
      <?php if ($profile['Location']): ?>
        <div class="filter-data">
          <?php echo $profile['Location']['name'] ?>
        </div>
      <?php endif ?>
    <?php if (!$showImage): ?></td><?php endif ?>
  <?php endif ?>
  <?php if (!isset($hide['event_count'])): ?> 
    <?php if (!$showImage): ?><td class="event-count standard"><?php endif ?>
      <?php if ($profile['event_count']): ?>
        <?php echo link_to($profile['event_count'] . ' ' . pluralize('event', $profile['event_count']), 'profile_show', 
                            Profile::parameterize($profile), array('class' => 'details')) ?>        
      <?php endif ?>
    <?php if (!$showImage): ?></td><?php endif ?>
  <?php endif ?>
  <?php if ($showImage): ?></td><?php endif ?>
</tr>