<td class="a-admin-text name"><?php echo link_to($event->getName(), 'event_admin_edit', $event) ?></td>
<td class="a-admin-text venue"><?php echo get_partial('eventAdmin/venue', array('type' => 'list', 'event' => $event)) ?></td>
<td class="a-admin-text profiles"><?php echo get_partial('eventAdmin/profiles', array('type' => 'list', 'event' => $event)) ?></td>
<td class="a-admin-date created_at"><?php echo false !== strtotime($event->getCreatedAt()) ? format_date($event->getCreatedAt(), "f") : '&nbsp;' ?></td>
