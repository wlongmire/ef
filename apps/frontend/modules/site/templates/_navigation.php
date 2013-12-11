<ul class="dropdown-nav clearfix">
  <?php foreach($navigation as $index => $navItem): ?>
    <li <?php echo_if(isset($navItem['right-align']), 'class="right-menu"') ?>>
      <a href="<?php echo_if($navItem['url'][0] == '/', $prefix) ?><?php echo $navItem['url'] ?>" <?php echo_if($active == $index, 'class="active"') ?>
         ><?php echo $navItem['title'] ?> <?php echo_if($navItem['children'], ' ▼') ?></a>
      <?php if ($navItem['children']): ?>
        <ul class="dropdown-list">
          <?php foreach($navItem['children'] as $navItemChild): ?>
            <li><a href="<?php echo_if($navItemChild['url'][0] == '/', $prefix) ?><?php echo $navItemChild['url'] ?>"
                   ><?php echo $navItemChild['title'] ?></a></li>
          <?php endforeach ?>
        </ul>        
      <?php endif ?>
    </li>
  <?php endforeach ?>
</ul>