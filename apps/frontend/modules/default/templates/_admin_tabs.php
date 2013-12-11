<div class="my-tabs big left">
  <ul>
    <?php foreach($tabs as $name => $settings): ?>
      <?php if (!isset($settings['credentials']) || $sf_user->hasCredential($settings['credentials'])): ?>
        <li <?php echo $tab == $name ? 'class="selected"' : '' ?>>
          <?php echo link_to($settings['label'], $settings['route']) ?>
        </li>
      <?php endif ?>
    <?php endforeach ?>
  </ul>
</div>