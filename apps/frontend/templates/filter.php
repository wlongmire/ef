<?php include_partial('default/preBody') ?>
<?php $bodyAttributes = array(
    'data-reload-url' => url_for($sf_context->getModuleName() == 'profile' ? 'profile_index' : 'event_index'),
    'class' => 'listings'
) ?>
<?php if ($sf_request['iframe']): ?>
  <?php $iframeView = (!$sf_request['view'] || $sf_request['view'] != 'full') ? 'listings' : 'full' ?>
  <?php $defaultWidth = ($iframeView == 'full' ? '970' : '665') ?>
  <?php $bodyAttributes['class'] .= ' iframe ' . ($iframeView == 'full' ? strtolower($_site->getLayout()) : 'listings-only') ?>
  <?php $bodyAttributes['data-iframe'] = $sf_request['iframe'] ?>
  <?php $bodyAttributes['style'] = 'width: ' .  ($sf_request['width'] ? $sf_request['width'] : $defaultWidth) . 'px' ?>
<?php else: ?>    
  <?php $iframeView = null ?>
  <?php $bodyAttributes['class'] .= ' full ' . strtolower($_site->getLayout()) ?>
<?php endif ?>
<?php echo tag('body', $bodyAttributes, true) ?>
<?php if (!$sf_request['iframe']): ?>
  <?php include_partial('a/globalTools') ?>
  <div id="faux-body">
    <?php include_partial('default/header') ?>
<?php endif ?>
<div id="content" class="a-content clearfix">
  <?php $referer = $sf_request->getReferer() ?>  
  <?php if (!$sf_request['iframe'] || $iframeView == 'full'): ?>
    <div class="filters page-component accessory filter-scope" id="filters">
      <div class="filter-area details">
        <div class="loading">Loading details</div>
        <div class="data">            
          <?php if (has_slot('filter-details')): ?>
            <?php include_slot('filter-details') ?>
          <?php else: ?>
            Click items to see details about them.
          <?php endif ?>
        </div>
      </div>
    </div>
  <?php endif ?>    
  <?php if (has_slot('filter-results')): ?>
    <?php if (!$sf_request['iframe']): ?>
      <?php include_partial('default/flashes') ?>
    <?php endif ?>  
    <div id="filter-results" <?php echo_if(!$iframeView || $iframeView == 'full', 'class="page-component filter-results main"') ?>>
      <?php if (has_slot('filter-results-header')): ?>
        <?php include_slot('filter-results-header') ?>
      <?php endif ?>
      <div class="loading clear">
        Loading results
      </div>
      <div class="data">
        <?php include_slot('filter-results') ?>
      </div>
    </div>
  <?php endif ?>
  <?php echo $sf_content ?>
</div>      
<?php if (!$sf_request['iframe']): ?>
  <?php if ($_site->displayTextFooter()): ?>
    <?php a_slot('footer-area', 'aRichText', array(
        'slug' => $_site->generateVirtualPageSlug('PHLocal'), //shared across all sites,
        'edit' => $sf_user->hasCredential('admin')
    )) ?>
  <?php endif ?> 
  </div>
<?php endif ?>
<?php include_partial('default/googleAnalytics') ?>  
<?php include_partial('a/globalJavascripts') ?>     
</body>
</html>
