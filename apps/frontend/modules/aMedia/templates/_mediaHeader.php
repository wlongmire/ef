<?php use_helper('a') ?>
<?php $page = aTools::getCurrentPage() ?>
<?php if ($page->admin): ?>

	<div class="a-ui a-admin-header">
	  <ul class="a-ui a-controls a-admin-controls">
			<li><h3 class="a-admin-title"><?php echo link_to('<span class="icon"></span>'.__('Media Library', null, 'apostrophe'), '@a_media_index', array('class' => 'a-btn big lite'))?></h3></li>
	  	<?php if (aMediaTools::userHasUploadPrivilege() && ($uploadAllowed || $embedAllowed)): ?>
	  	  <?php // This is important because sometimes you are selecting specific media types ?>
	      <?php $typeLabel = aMediaTools::getBestTypeLabel() ?>
		    <li><a href="<?php echo url_for("aMedia/resume?add=1") ?>" id="a-media-add-button" class="a-btn icon big a-add"><span class="icon"></span><?php echo a_('Add  ' . $typeLabel) ?></a></li>
			<?php endif ?>
		</ul>
	</div>

	<?php a_js_call('apostrophe.clickOnce(?)', '#a-save-media-selection,.a-media-select-video,.a-select-cancel') ?>

	<?php if (aMediaTools::isSelecting()): ?>
		<?php a_js_call('apostrophe.mediaClearSelectingOnNavAway(?)', url_for('aMedia/clearSelecting')) ?>	
	<?php endif ?>

<?php endif ?>
