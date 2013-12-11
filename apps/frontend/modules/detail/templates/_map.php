<?php $address = $addressable->getAddress(array('single_line' => true)) ?>
<?php if ($address): ?>
  <div class="section">
    <div class="google-map">
      <a href="<?php echo map_url($address) ?>" target="_blank" class="reset">
        <div class="map-cover"> View Larger Map</div>
        <img src="<?php echo map_image_url($address, array('width' => 225, 'height' => 225)) ?>" alt="Map Of Address"/>
      </a>
    </div>
    <div class="media-description">
      <?php if (isset($title)): ?>
        <?php echo $title ?><br/>
      <?php endif ?>
      <?php echo $addressable->getAddress(array('single_line' => false)) ?>
      <br/>
      <a href="<?php echo map_url($address) ?>" target="_blank">View Map</a>
    </div>
  </div>
<?php endif ?>