<?php extract(array(
          'cancel' => true
      ), EXTR_SKIP) ?>
<?php if ($sf_user->hasFlash('wf-feedback-success')): ?>
  <div class="success flash"><?php echo $sf_user->getFlash('wf-feedback-success') ?></div>
<?php endif ?>
<form action="<?php echo url_for('wf_feedback_submit') ?>" method="post"
      class="wf-feedback-form <?php if ($form->getOption('form_class')) echo $form->getOption('form_class') ?>">
  <ul>
    <?php echo $form ?>
    <li class="submission">
      <?php if ($form->getOption('submit_button')): ?>
        <?php echo $form->getOption('submit_button') ?>
      <?php else: ?>
        <button type="submit" <?php if ($form->getOption('submit_button_class')) echo sprintf('class="%s"', $form->getOption('submit_button_class')) ?>>Send</button>
      <?php endif ?>
      <?php if ($cancel && $url = $form['return_url']->getValue()): ?>
        <?php if ($form->getOption('cancel_button')): ?>
          <?php echo sprintf($form->getOption('cancel_button'), $url) ?>
        <?php else: ?>
          <a href="<?php echo $url ?>" <?php if ($form->getOption('cancel_button_class')) echo sprintf('class="%s"', $form->getOption('cancel_button_class')) ?>>Cancel</a>
        <?php endif ?>
      <?php endif ?>
    </li>
  </ul>
</form>