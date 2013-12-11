<h1>Login As</h1>
<?php include_partial('default/flashes') ?>
<form method="POST" action="<?php echo url_for('default', array('action' => 'loginAs', 'module' => 'default')) ?>">
  <fieldset>
    <label for="email">Email</label>
    <input type="text" name="email"/>
    <input type="submit" value="Login"/>
    <div class="help standard">
      Email or full name
    </div>
  </fieldset>
</form>