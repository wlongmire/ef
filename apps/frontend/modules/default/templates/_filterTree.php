<?php $active = isset($active) ? $sf_data->getRaw('active') : null ?>
<?php $tree = isset($tree) ? $sf_data->getRaw('tree') : null ?>
<?php $currentLevel = isset($currentLevel) ? $sf_data->getRaw('currentLevel') : 0 ?>

<?php $filterClass = 'filter-tree ' . $type . ' ' ?>
<?php $filterClass .= in_array($type, array('radio', 'checkbox')) ? 'removable widget ' : '' ?>
<?php $filterClass .= $type == 'checkbox' ? 'multi-select' : 'single-select' ?>
<ul class="<?php echo $filterClass ?>">
<?php if (isset($primary) && $primary): ?>
  <li <?php echo_if($active !== null && !$active, 'class="active"') ?>>
    <?php echo link_to($primary, $route, array('filter' => $filter, 'value' => 0, 'label' => myTools::urlify($primary)), 
                array('class' => 'filter primary', 'data-filter' => $filter)) ?>
  </li>
<?php endif ?>
<?php 
$pendingItems = array();
while (!empty($tree)) 
{
  $node = array_shift($tree);
  $hasChildren = $node['lft'] + 1 != $node['rgt'];
  $isActive = is_array($active) ? in_array($node['id'], $active) : $node['id'] == $active;
  // Level down?
  if ($node['level'] > $currentLevel) 
  {
    echo '<ul>';
  }
  // Level up?
  if ($node['level'] < $currentLevel) 
  {
    // Yes, close n open <ul>
    echo str_repeat('</ul></li>', $currentLevel - $node['level']);
  }
  ?>
  <li <?php echo_if($type == 'filter' && $isActive, 'class="active"') ?>>
    <?php if ($type == 'filter'): ?>
      <?php echo link_to($node[$column], $route, 
                          array('filter' => $filter, 'value' => $node['id'], 'label' => myTools::urlify($node[$column])),
                          array('class' => 'filter', 'data-filter' => $filter)) ?>
    <?php elseif ($type == 'radio' || $type == 'checkbox'): ?>
      <?php $htmlId = $widget->generateId($name . '[]', $node['id']) ?>
      <?php if ($type == 'radio'): ?>
        <input type="radio" name="<?php echo $name ?>" value="<?php echo $node['id'] ?>" id="<?php echo $htmlId ?>"
          <?php echo_if($isActive, 'checked="checked"') ?>>
      <?php else: ?>
        <input type="checkbox" name="<?php echo $name ?>[]" value="<?php echo $node['id'] ?>" id="<?php echo $htmlId ?>"       
               <?php echo_if($isActive, 'checked="checked"') ?>>
      <?php endif ?>
      <label for="<?php echo $htmlId ?>" <?php echo_if($isActive, 'class="active"') ?>
             ><?php echo $node[$column] ?></label>
    <?php endif ?>
  <?php if (!$hasChildren): ?>
    </li>
  <?php endif ?>
  <?php
  // Adjust current depth
  $currentLevel = $node['level'];
}
echo str_repeat('</ul>', $currentLevel);
?>
</ul>
<?php if (!sfConfig::get('filter_tree_js_included')): ?>
  <?php sfConfig::set('filter_tree_js_included', true) ?>
  <?php a_js_call('ef.filterTrees(?)', '.filter-tree') ?>
<?php endif ?>
