<h1 class="page-title">Join EventsFilter</h1>
<?php a_area('body', array(
    'allowed_types' => array(
		'aRichText', 
		'aSlideshow', 
		'aFile',
		'aRawHTML', 		
	),
    'slug' => 'join'
)) ?>
<?php if ($profile): ?>
  <div class="message light">
    <h3>Claiming <?php echo $profile['name'] ?> profile.</h3>
      Your new account will be associated with this existing profile. 
      To create a new account without an association, go <?php echo link_to('here', 'join') ?>.
  </div>
<?php endif ?>
<?php $sf_response->setTitle('Create Profile') ?>
<form class="standard-form validate" method="post"
      action="<?php echo url_for('join', $profile ? array('claim_token' => $profile->getClaimToken()) : array()) ?>">
  <div class="a-admin-form-container">
    <fieldset>
      <?php echo $form->renderGlobalErrors() ?>
      <?php echo $form->renderHiddenFields() ?>
      <h4>Account Information</h4>
      <?php echo $form['email_address']->renderRow() ?>
      <?php echo $form['first_name']->renderRow() ?>
      <?php echo $form['last_name']->renderRow() ?>
    </fieldset>
    <fieldset>
      <h4>Password</h4>
      <?php echo $form['password']->renderRow() ?>
      <?php echo $form['password_again']->renderRow() ?>
    </fieldset>
    <div class="a-form-row submit">
      <ul class="a-ui a-controls">  
        <li>
          <?php echo a_submit_button('Save', array('big')) ?>
        </li>
      </ul>
    </div>
  </div>
</form>