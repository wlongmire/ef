<form class="standard-form validate clearfix" method="post"
      action="<?php echo url_for('user_edit') ?>">
  <div class="a-admin-form-container">
    <fieldset>
      <?php echo $form->renderGlobalErrors() ?>
      <?php echo $form->renderHiddenFields() ?>
      <h4>Password</h4>
      <?php echo $form['password']->renderRow() ?>
      <?php echo $form['password_again']->renderRow() ?>
    </fieldset>
    <div class="a-form-row submit">
      <ul class="a-ui a-controls">  
        <li>
          <?php echo a_submit_button('Change Password', array('big')) ?>
        </li>
      </ul>
    </div>
  </div>
</form>