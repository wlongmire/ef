<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<link rel="shortcut icon" href="/favicon.ico" />
  <?php use_stylesheet($_site->getLayout() . '.less', '', array('data-asset-group' => 'global')) ?>
	<?php include_http_metas() ?>
	<?php include_metas() ?>
  <?php $title = $sf_context->getResponse()->getTitle() ?: get_default_title($_site) ?>
	<?php echo content_tag('title', $_site->org_name . ': ' . $title) ?>
  <?php a_include_stylesheets() ?>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>
  <?php a_include_javascripts() ?>
  <?php if ($_site->getUrl() == 'designphiladelphia.org'): ?>
    <link href="http://fonts.googleapis.com/css?family=Cabin:400,400italic,700,700italic,|PT+Serif:r,b,i,bi" rel="stylesheet" type="text/css">
  <?php endif ?>
  <?php if ($sf_request['iframe']): ?>
    <base target="_blank" />
    <style type="text/css">
      <?php if ($sf_request['link']): ?>
        a {color: <?php echo css_color($sf_request['link']) ?>; }
      <?php endif ?>
      <?php if ($sf_request['bg_even']): ?>
        .filter-list .filter-data tr.even { background: <?php echo css_color($sf_request['bg_even']) ?>; }
      <?php endif ?>
      <?php if ($sf_request['bg_odd']): ?>
        .filter-list .filter-data tr.odd { background: <?php echo css_color($sf_request['bg_odd']) ?>; }
      <?php endif ?>
      <?php if ($sf_request['text']): ?>
        body { color: <?php echo css_color($sf_request['text']) ?>; }
      <?php endif ?>
      <?php if ($sf_request['header']): ?>
        .filter-list .header.date { color: <?php echo css_color($sf_request['header']) ?>; border-color: <?php echo css_color($sf_request['header']) ?>; }
      <?php endif ?>
      <?php if ($sf_request['font'] == 'monospace'): ?>
        body { font-family: "Courier New", "Andale Mono", monospace;}
      <?php elseif ($sf_request['font'] == 'serif'): ?>
        body { font-family: "Times New Roman", "DejaVu Serif", serif; }
      <?php endif ?>
    </style>
  <?php endif ?>
</head>