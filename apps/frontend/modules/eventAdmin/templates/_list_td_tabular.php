<td class="a-admin-text name">
  <?php echo link_to($event->getName(), 'event_admin_edit', $event) ?>
  <br/>
  <?php echo $event->EventType->name ?>
</td> 
<td class="a-admin-text published"><?php echo $event->is_published ?
      '<img alt="Checked" title="Checked" src="/apostrophePlugin/images/tick.png">' :
      '<span class="empty">(pending)</span>' ?></td>
<td>
  <?php foreach($event->Owners as $gu): ?>
    <?php $text = array() ?>
    <?php if ($sf_user->hasCredential('admin')): ?>
      <?php $text[] = link_to($gu->first_name . ' ' . $gu->last_name, 'user_admin_edit', $gu) ?>
    <?php else: ?>
      <?php $text[] = $gu->first_name . ' ' . $gu->last_name ?>
    <?php endif ?>
    <?php echo implode(',', $text) ?>
  <?php endforeach ?>
</td>
<td class="a-admin-text venue"><?php echo get_partial('eventAdmin/venue', array('type' => 'list', 'event' => $event)) ?></td>
<td class="a-admin-text profiles"><?php echo get_partial('eventAdmin/profiles', array('type' => 'list', 'event' => $event)) ?></td>
<?php if ($sf_user->hasCredential('admin')): ?>
  <td class="a-admin-date created_at"><?php echo false !== strtotime($event->getCreatedAt()) ? format_date($event->getCreatedAt(), "f") : '&nbsp;' ?></td>
<?php endif ?>
