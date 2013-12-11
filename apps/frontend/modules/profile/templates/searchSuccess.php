<?php slot('body_class') ?>listings search-only<?php end_slot() ?>
<form method="post" class="filter-form standard-form search-form" id="filters"
    data-search-url="<?php echo url_for('profile_toggle_filter', array('filter' => 'name')) ?>">
  <div><?php echo $form['name']->render(array('placeholder' => 'Search')) ?></div>
</form>  
<div id="filter-results">
  <div class="loading">Search profiles</div>
  <div class="data">
    <?php include_partial('profile/list', array(
        'filters' => $filters,
        'pager' => $pager,
        'profiles' => $profiles,
        'applied_filters' => $applied_filters
    )) ?>
  </div>
</div>