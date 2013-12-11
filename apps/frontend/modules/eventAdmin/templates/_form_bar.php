<div class="a-admin-bar">
	<h2 class="a-admin-title you-are-here"><?php echo $title ?></h2>
  <?php if (isset($event) && $event->exists()): ?>
    <?php echo link_to('View this event', 'event_show', $event) ?><br/>
  <?php endif ?>
  <div class="return-link">
    <?php echo link_to('&laquo; Back to Event Admin', 'event_admin') ?>
  </div>
</div>