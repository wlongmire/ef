[?php use_helper('a')?]

[?php slot('body_class') ?]a-admin a-admin-generator [?php echo $sf_params->get('module'); ?] [?php echo $sf_params->get('action');?] [?php end_slot() ?]

<?php if (isset($this->params['css'])): ?> 

	[?php use_stylesheet('<?php echo $this->params['css'] ?>', 'first') ?] 

<?php endif; ?>

[?php use_javascript('plugin/jquery.treeTable.js', '', array('data-group' => 'admin')) ?]
[?php use_javascript('admin/tree-admin.js', '', array('data-group' => 'admin')) ?]
[?php use_stylesheet('plugin/jquery.treeTable.css', '', array('data-group' => 'admin')) ?]
[?php use_stylesheet('admin/tree-admin.less', '', array('data-group' => 'admin')) ?]

[?php aTools::setAllowSlotEditing(false); ?]