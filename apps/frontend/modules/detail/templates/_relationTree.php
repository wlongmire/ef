<?php extract(array(
    'class' => 'relation-tree',
    'column' => 'name',
    'separator' => '&raquo;'
), EXTR_SKIP) ?>
<div class="<?php echo $class ?>">
  <?php if ($ancestors): ?>
    <?php foreach($ancestors as $ancestor): ?>
      <?php echo $ancestor[$column] ?> <span class="separator"><?php echo $separator ?></span>
    <?php endforeach ?>
  <?php endif ?>
  <?php echo $record[$column] ?>
</div>