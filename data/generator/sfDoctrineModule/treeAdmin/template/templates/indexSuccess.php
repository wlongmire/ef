[?php use_helper('a', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

[?php slot('a-page-header')?]
<div class="a-ui a-admin-header">
	<h3 class="a-admin-title">[?php echo __('<?php echo $this->configuration->getValue('list.title') ?>', array(), 'apostrophe') ?]</h3>  
	<ul class="a-ui a-controls a-admin-controls">
    <?php if ($this->configuration->hasFilterForm()): ?>
      <li class="a-admin-actions-filter">
        [?php echo a_js_button(a_('Filter'), array('icon','a-filters','alt','big'), 'a-admin-filters-open-button') ?]
      </li>
    <?php endif ?>    
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]   
  </ul>
  [?php include_partial('<?php echo $this->getModuleName() ?>/list_header', array('pager' => $pager)) ?]    
</div>
[?php end_slot() ?]

<div class="a-ui a-admin-container [?php echo $sf_params->get('module') ?]">

	<div class="a-admin-content main">
		
		<?php if ($this->configuration->hasFilterForm()): ?>
		  [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'configuration' => $configuration, 'filtersActive' => $filtersActive)) ?]
		<?php endif; ?>

		[?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]
		
    <form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post" id="a-admin-batch-form">
		
      [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'configuration' => $configuration)) ?]

      <ul class="a-ui a-admin-actions">
        [?php include_partial('<?php echo $this->getModuleName() ?>/list_batch_actions', array('helper' => $helper)) ?]
      </ul>

    </form>
		
	</div>

  <div class="a-admin-footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_footer', array('pager' => $pager)) ?]
  </div>

</div>

[?php a_js_call('apostrophe.aAdminEnableFilters()') ?]