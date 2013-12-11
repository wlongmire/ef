<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>
<form class="standard-form validate  clearfix" method="post"
      action="<?php echo url_for('user_edit') ?><?php echo_if($sf_request['profile'], '?profile=1') ?>">
    <fieldset>
      <?php echo $form->renderGlobalErrors() ?>
      <?php echo $form->renderHiddenFields() ?>
      <h4>Account Information</h4>
      <?php echo $form['email_address']->renderRow() ?>
      <?php echo $form['first_name']->renderRow() ?>
      <?php echo $form['last_name']->renderRow() ?>
    </fieldset>
    <div class="a-form-row submit">
      <ul class="a-ui a-controls">  
        <li>
          <?php echo a_submit_button('Update Information', array('big')) ?>
        </li>
      </ul>
    </div>
</form>