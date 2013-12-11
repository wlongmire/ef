<div class="a-admin-bar" <?php if (count($sf_user->getAttribute('eventAdmin.filters', null, 'admin_module'))): ?>class="has-filters"<?php endif ?>>
	<h2 class="a-admin-title you-are-here"><?php echo $sf_user->hasCredential('admin') ? __('Event Admin') : __('Your Events') ?></h2>
</div>