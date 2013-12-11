<div class="a-admin-bar">
	<h2 class="a-admin-title you-are-here">[?php echo $title ?]</h2>
  <div class="return-link">
    [?php echo link_to('&laquo; Back to <?php echo $this->configuration->getValue('list.title') ?>', '<?php echo $this->params['route_prefix'] ?>') ?]
  </div>
</div>