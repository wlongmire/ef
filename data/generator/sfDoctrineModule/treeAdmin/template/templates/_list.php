<?php $treeColumn = 'name' ?>
<div class="a-admin-list">
  [?php if (!$pager->getNbResults()): ?]
    <p>[?php echo __('No result', array(), 'apostrophe') ?]</p>
  [?php else: ?]
    <div class="admin-control hidden tree-order-changed clearfix">
      <h3>Tree Order Changed</h3>
      <p>When you're done re-ording the tree, click the button below.</p>
      <ul class="a-ui a-controls a-admin-controls">
        <li><a href="#" id="update-tree-button" class="a-btn big" 
         data-action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'updateTree')) ?]">Update Tree</a>
        </li>
      </ul>
    </div>
    <table cellspacing="0" class="a-admin-list-table" id="tree-list">
      <thead>
        <tr>
					<?php if ($this->configuration->getValue('list.batch_actions')): ?>
          	<th id="a-admin-list-batch-actions"><input id="a-admin-list-batch-checkbox-toggle" class="a-admin-list-batch-checkbox-toggle a-checkbox" type="checkbox"/></th>
					<?php endif; ?>
          	[?php include_partial('<?php echo $this->getModuleName() ?>/list_th', array('sort' => $sort, 'helper' => $helper)) ?]
					<?php if ($this->configuration->getValue('list.object_actions')): ?>
          	<th id="a-admin-list-th-actions">[?php echo __('Actions', array(), 'apostrophe') ?]</th>
					<?php endif; ?>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan="<?php echo count($this->configuration->getValue('list.display')) + ($this->configuration->getValue('list.object_actions') ? 1 : 0) + ($this->configuration->getValue('list.batch_actions') ? 1 : 0) ?>">
						<h6 class="a-admin-list-results">
	            [?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'apostrophe') ?]
	            [?php if ($pager->haveToPaginate()): ?]
	              [?php // echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'apostrophe') ?]
	            [?php endif; ?]
						</h6>
            [?php if ($pager->haveToPaginate()): ?]
              [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager)) ?]
            [?php endif; ?]	
          </th>
        </tr>
      </tfoot>
      <tbody>
        [?php $total = $pager->getNbResults(); foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]
          <tr id="node-[?php echo $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]" class="tree-admin-row [?php echo $helper->getTreeClass($<?php echo $this->getSingularName() ?>) ?] [?php echo $odd ?]" data-id="[?php echo $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]">
						<?php if ($this->configuration->getValue('list.batch_actions')): ?>
            	[?php include_partial('<?php echo $this->getModuleName() ?>/list_td_batch_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
						<?php endif; ?>
            [?php include_partial('<?php echo $this->getModuleName() ?>/list_td', array(
                '<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>,
                'configuration' => $configuration,
                'helper' => $helper
            )) ?]
						<?php if ($this->configuration->getValue('list.object_actions')): ?>
            	[?php include_partial('<?php echo $this->getModuleName() ?>/list_td_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
						<?php endif; ?>
          </tr>
        [?php endforeach; ?]
      </tbody>
    </table>
  [?php endif; ?]
</div>