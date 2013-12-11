  <form method="post" class="filter-form standard-form" 
        data-start-date-url="<?php echo url_for('event_toggle_filter', array('filter' => 'start_date')) ?>"
        data-date-range-url="<?php echo url_for('event_toggle_filter', array('filter' => 'date_range')) ?>">
    <?php echo $dateForm['start_date'] ?>
    <?php echo $dateForm['date_range']->renderLabel('for') ?>
    <?php echo $dateForm['date_range'] ?>
  </form>