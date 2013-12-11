<p class="notice">This will not be added unless you click save, below.</p>
<?php echo $form ?>
<?php include_component('wfAddons', 'feedbackForm', array(
          'returnUrl' => url_for(sfContext::getInstance()->getRouting()->getCurrentRouteName(), $sf_request->getParameterHolder()->getAll(), true),
          'cancel' => false
      )) ?>