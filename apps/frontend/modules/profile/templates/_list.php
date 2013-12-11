<?php use_helper('Text') ?>
<div id="filter-response" class="filter-response filter-list">
  <?php if (count($profiles)): ?>
    <?php $row = 0 ?>  
    <?php $highlight = isset($filters['name']) ? $filters['name'] : false ?>
    <?php $showImages = isset($sf_request['images']) ? //arg must take precedence
            $sf_request['images'] != 'no' :
            $_site->alwaysShowImages() || $sf_user->getAttribute('profile.show_images', !$sf_request['iframe'] || $sf_request['images'] != 'no') ?>  
    <table class="filter-data profile <?php echo $showImages ? 'with-images' : 'no-images' ?>"> 
      <?php foreach($profiles as $profile): ?>
        <?php include_partial('profile/listItem', array(
            'profile' => $profile,
            'highlight' => $highlight,
            'showImage' => $showImages,
            'class' => parity(++$row)
        )) ?>
      <?php endforeach ?>
    </table>
      <?php if (!$pager->isLastPage()): ?>
        <div class="a-ui pagination clearfix">
          <?php echo a_link_button('Show More Profiles', 'profile_paginate', array('page' => $pager->getPage() + 1), array('paginate', 'big')) ?>
        </div>
      <?php endif ?>    
  <?php else: ?>
    <div class="no-results">
      No matching profiles found.
    </div>
  <?php endif ?>
</div>