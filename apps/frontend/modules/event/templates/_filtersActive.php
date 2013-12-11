<h3>Active Filters</h3>
<ul>
  <?php foreach($filters as $filter => $value): ?>
    <li>
      <?php echo $filter ?>: <?php echo $value instanceof DateTime ? $value->format('m/d/Y') : $value ?>
      <?php //echo link_to('remove', 'event_toggle_filter', array('filter' => $filter)) ?>
    </li>
  <?php endforeach ?>
</ul>

