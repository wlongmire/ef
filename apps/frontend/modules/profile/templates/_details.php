<?php if (!$sf_request['callback']): ?>
  <?php include_partial('default/adDetails', array(
    'attribute' => 'profile.filters'
)) ?>
<?php endif ?>
<?php $useAbsoluteUrls = (boolean)$sf_request['callback'] ?>
<div class="details">
  <div class="section">
    <?php if (!$sf_request['callback']): ?>
      <h2><?php echo $profile['name'] ?></h2>
    <?php else: ?>
      <a href="javascript:;" class="close-details" style="float: right">X</a>
      <h4><?php echo $profile['name'] ?></h4>
    <?php endif ?>    
    <?php if ($profile['display_email'] && $profile['User']['email_address']): ?>
      <div class="contact"><?php echo aHtml::obfuscateMailto(mail_to($profile['User']['email_address'])) ?></div>
    <?php endif ?>
    <?php $names = wfToolkit::arrayPluck($categories, 'name') ?>
    <?php if ($names): ?><div class="categories"><?php echo implode(', ', $names) ?></div><?php endif ?>
    <?php $names = wfToolkit::arrayPluck($disciplines, 'name') ?>
    <?php if ($names): ?><div class="disciplines"><?php echo implode(', ', $names) ?></div><?php endif ?>
    <?php if (count($tags)): ?>
      <div class="tags clearfix">
        <span class="plain icon alt a-tags"><span class="icon"></span></span>
        <?php echo implode(', ', $tags) ?>
      </div>
    <?php endif ?>
  </div>
  <?php if ($profile->media_item_id): ?>
    <div class="section">
      <?php include_partial('aMedia/image', array(
        'item' => $profile->Picture,
        'variant' => 'detail'
      )) ?>
      <?php if ($profile->Picture->description): ?>
        <div class="media-description">
          <?php echo $profile->Picture->description ?>
        </div>
      <?php endif ?>
    </div>
  <?php endif ?>
  <?php if ($profile['blurb']): ?>
    <?php echo expandable_blurb($profile['blurb']) ?>
  <?php endif ?>
  <?php include_partial('event/listForDetails', array(
      'events' => $events,
      'filter_url' => url_for('event_toggle_filter', 
                               array('filter' => 'profile', 'value' => $profile['id'], 'label' => myTools::urlify($profile['name'])))
  )) ?>
  <?php if (count($groupsOrMembers)): ?>
    <div class="section inset">
      <div class="filter-header">
        <h4><?php echo $groupsOrMembersTitle ?></h4>
        <?php if (isset($filterUrl)): ?>       
          <ul class="a-ui a-controls">
            <li>
              <a href="<?php echo $filterUrl ?>" class="a-btn filter">View List</a>
            </li>
          </ul>
        <?php endif ?>
      </div>
      <ul class="info-list details clearfix">
        <?php foreach($groupsOrMembers as $groupOrMember): ?>
          <li>
            <?php echo link_to($groupOrMember['name'], 'profile_show', $groupOrMember, 
                            array('class' => 'details', 'absolute' => $useAbsoluteUrls)) ?>
          </li>
        <?php endforeach ?>          
      </ul>      
    </div>
  <?php endif ?>
  
  <?php include_partial('event/listForDetails', array(
      'events' => $pastEvents,
      'title' => 'Past Events',
      'filter_url' => url_for('event_set_profile_with_past_hack', 
                                array('filter' => 'profile', 'value' => $profile['id'], 'label' => myTools::urlify($profile['name'])))
  )) ?>  
  <?php foreach($profile['Urls'] as $url): ?>
    <?php if ($url['type'] == 'personal'): ?>
      <div class="website">
        <a href="<?php echo aUrl::addProtocol($url['url']) ?>" title="<?php echo $url['url'] ?>" target="_blank">View Website</a>
      </div>
    <?php endif ?>
  <?php endforeach ?>
</div>