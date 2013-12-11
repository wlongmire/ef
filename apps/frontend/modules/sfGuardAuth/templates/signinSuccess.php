<?php use_helper('I18N') ?>

<h1 class="page-title">Sign In</h1>

<p>
  Don't have an account? <?php echo link_to('Join!', 'join') ?>
</p>

<?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>