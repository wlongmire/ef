<?php use_helper('I18N') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <div>
    <?php echo $form ?>
    <?php if (isset($routes['sf_guard_forgot_password'])): ?>
      <a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('Forgot your password?', null, 'sf_guard') ?></a>
    <?php endif; ?>    
    <div class="a-form-row submit">
      <ul class="a-ui a-controls">  
        <li>
          <?php echo a_submit_button('Sign In', array('big')) ?>
        </li>
      </ul>
    </div>
  </div>
</form>