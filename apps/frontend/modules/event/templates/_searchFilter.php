<form method="post" class="filter-form standard-form"
      data-search-url="<?php echo url_for('event_toggle_filter', array('filter' => 'name')) ?>">
  <div><?php echo $form['name']->render() ?></div>
</form>