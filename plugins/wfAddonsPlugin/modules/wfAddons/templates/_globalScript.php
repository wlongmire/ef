<?php javascript_tag() ?>
  var <?php echo sfConfig::get('app_wf_global_js_var', 'global') ?> = <?php echo json_encode(array(
      'form' => wfFormSymfony::getJavascriptValues()
  )) ?>;
<?php end_javascript_tag() ?>
