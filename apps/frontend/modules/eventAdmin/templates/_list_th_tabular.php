
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-name">
		  <?php if ('name' == $sort[0]): ?>
	    <?php echo link_to(__('Name', array(), 'messages'), 'eventAdmin/index?sort=name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?>
	    <?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'apostrophe'), 'title' => __($sort[1], array(), 'apostrophe'))) ?>
	  <?php else: ?>
	    <?php echo link_to(__('Name', array(), 'messages'), 'eventAdmin/index?sort=name&sort_type=asc') ?>
	  <?php endif; ?>
</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
<th class="a-admin-text">
  Published
</th>
<th>
  Owner(s)
</th>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-venue">
		  <?php echo __('Venue', array(), 'messages') ?>
		</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>

	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-profiles">
		  <?php echo __('Profiles', array(), 'messages') ?>
		</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
<?php if ($sf_user->hasCredential('admin')): ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-date a-admin-list-th-created_at">
		  <?php if ('created_at' == $sort[0]): ?>
	    <?php echo link_to(__('Created', array(), 'messages'), 'eventAdmin/index?sort=created_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?>
	    <?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'apostrophe'), 'title' => __($sort[1], array(), 'apostrophe'))) ?>
	  <?php else: ?>
	    <?php echo link_to(__('Created', array(), 'messages'), 'eventAdmin/index?sort=created_at&sort_type=asc') ?>
	  <?php endif; ?>
		</th>
	<?php end_slot(); ?>

  <?php include_slot('a-admin.current-header') ?>
<?php endif ?>