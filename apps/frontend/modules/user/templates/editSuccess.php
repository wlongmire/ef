<?php include_partial('user/accountHeader', array(
    'user' => $passwordForm->getObject(),
    'tab' => 'account'
)) ?>
<h2 class="page-title">Account</h2>
<?php include_partial('default/flashes') ?>
<div class="twin-forms clearfix">
  <div class="primary form">
    <?php include_partial('user/formEdit', array(
        'form' => $editForm
    )) ?>    
  </div>
  <div class="secondary form">
    <?php include_partial('user/formPassword', array(
        'form' => $passwordForm
    )) ?>    
  </div>  
</div>
