<?php include_partial('default/preBody') ?>
<?php $bodyClass = (has_slot('body_class') ? get_slot('body_class') . ' ' : '') . (has_slot('a-body-class') ? get_slot('a-body-class') : '') ?>
<body class="full <?php echo $bodyClass ?> <?php echo ($sf_user->isAuthenticated()) ? ' logged-in':' logged-out' ?><?php echo (sfConfig::get('app_a_js_debug', false)) ? ' js-debug':'' ?>">
  <?php include_partial('a/globalTools') ?>
  <div id="faux-body">
    <?php include_partial('default/header') ?>    
    <?php $hasSidebarContent = $_site->mode == Site::MODE_ENTRY && strpos($bodyClass, 'a-media') === false && strpos($bodyClass, 'a-blog') === false  ?>
    <?php if (!$hasSidebarContent): ?>
      <div class="page-component full clearfix">
      <?php if (has_slot('a-subnav')): ?>
        <?php include_slot('a-subnav') ?>
      <?php endif ?>            
    <?php endif ?>
    <div id="content" class="a-content clearfix">
      <?php if ($hasSidebarContent): ?>
        <div class="page-component accessory">
          <?php include_partial('default/sidebarNav') ?>
          <?php if (has_slot('a-subnav')): ?>
            <?php include_slot('a-subnav') ?>
          <?php endif ?>              
        </div>
        <div class="page-component main">
      <?php endif ?>
          
      <?php if (has_slot('a-search')): ?>
        <?php include_slot('a-search') ?>
      <?php endif ?>

      <?php if (has_slot('a-tabs')): ?>
        <?php include_slot('a-tabs') ?>
      <?php endif ?>

      <?php if (has_slot('a-breadcrumb')): ?>
        <?php include_slot('a-breadcrumb') ?>
      <?php endif ?>

      <?php if (has_slot('a-page-header')): ?>
        <?php include_slot('a-page-header') ?>
      <?php endif ?>
    
      <?php echo $sf_content ?>
          
      <?php if ($hasSidebarContent): ?>
        </div>
      <?php endif ?>
    </div>
    <?php if (!$hasSidebarContent): ?>
      </div>
    <?php endif ?>        
        
  </div>
	<?php include_partial('default/googleAnalytics') ?>  
	<?php include_partial('a/globalJavascripts') ?>  
</body>
</html>