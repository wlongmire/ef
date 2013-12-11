<?php foreach(array('success', 'error', 'notice') as $flash): ?>
  <?php if ($sf_user->hasFlash($flash)): ?>
      <div class="flash <?php echo $flash?>"><?php echo $sf_user->getFlash($flash) ?></div>
  <?php endif ?>
<?php endforeach ?>