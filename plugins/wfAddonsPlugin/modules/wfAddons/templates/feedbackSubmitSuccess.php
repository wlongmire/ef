<h2>Send Message</h2>
<?php if ($form->hasErrors()): ?>
  <div class="error flash">This form has an error.</div>
<?php endif ?>
<?php include_component('wfAddons', 'feedbackForm', array(
    'form' => $form
)) ?>