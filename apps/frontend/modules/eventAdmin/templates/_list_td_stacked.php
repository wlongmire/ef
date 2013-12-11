<td colspan="4">
  <?php echo __('%%name%% - %%venue%% - %%profiles%% - %%created_at%%', array('%%name%%' => link_to($event->getName(), 'event_admin_edit', $event), '%%venue%%' => get_partial('eventAdmin/venue', array('type' => 'list', 'event' => $event)), '%%profiles%%' => get_partial('eventAdmin/profiles', array('type' => 'list', 'event' => $event)), '%%created_at%%' => false !== strtotime($event->getCreatedAt()) ? format_date($event->getCreatedAt(), "f") : '&nbsp;'), 'messages') ?>
</td>
