<div class="viewing-message">
  <?php $viewingMessage = false ?>  
  <?php if (sfConfig::get('local_filter_title')): ?>
    <?php $viewingMessage = true ?>  
    <h2><?php echo sfConfig::get('local_filter_title') ?></h2>
  <?php elseif($_site->displayGeneratedLocalTitle()): ?>
    <?php $viewingMessage = true ?>    
    <?php if (isset($filters['profile']) && $filters['profile']['is_group']): ?>
      <h2>Artists in <?php echo $filters['profile']['name'] ?>
    <?php else: ?>
      <?php $type = isset($filters['event_type']) ? $filters['event_type']['name'] : 'Artists' ?>
      <?php $location = isset($filters['location']) ? $filters['location']['name'] : 'Philadelphia' ?>
      <h2><?php echo $type ?> in <?php echo $location ?></h2>
    <?php endif ?>
  <?php endif ?>
</div>
<?php sfConfig::set('has_viewing_message', $viewingMessage) ?>