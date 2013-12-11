<?php $active = isset($active) ? $sf_data->getRaw('active') : null ?>
<?php $tree = isset($tree) ? $sf_data->getRaw('tree') : null ?>
<select class="select-filter-tree" data-filter="<?php echo $filter ?>" 
        data-base-filter-url="<?php echo url_for($base_filter_route, array('filter' => $filter, 'value' => '-value-')) ?><?php echo_if($subfilter, '?subfilter=' . $subfilter) ?>">
  <option value="<?php echo isset($active) ? $active['id'] : '' ?>" selected="selected" class="title"
          ><?php echo $label ?>
  </option>
<?php 
$currentLevel = 0;  // -1 to get the outer <ul>
$pendingItems = array();
$baseLevel = isset($active) && $active ? ($active['level'] + 1) : 0;
while (!empty($tree)) 
{
  $node = array_shift($tree);
  ?>
  <option value="<?php echo $node['id'] ?>">
    <?php if (($node['level'] - $baseLevel) > 0): ?>
      <?php echo str_repeat('- ', $node['level'] - $baseLevel) ?>
    <?php endif ?>
    <?php echo $node['name'] ?>
  </option>
  <?php
}
?>
</select>