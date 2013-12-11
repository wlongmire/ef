<td class="event-admin-actions">
  <ul class="a-ui a-controls stacked clearfix">
    <li><?php echo my_a_link_button('Edit', 'event_admin_edit', $event, array('icon', 'a-edit')) ?></li>
    <li>
      <?php echo link_to(__('<span class="icon"></span>Copy', array(), 'messages'), 'eventAdmin/copy?id='.$event->getId(), array(  'class' => 'a-btn icon a-page-normal',)) ?>    </li>
    <?php echo $helper->linkToDelete($event, array(  'params' =>   array(  ),  'confirm' => 'Are you sure?',  'class_suffix' => 'delete',  'label' => 'Delete',)) ?>
  </ul>
</td>
