<div class="viewing-message">
  <?php $viewingMessage = false ?>
  <?php if (sfConfig::get('local_filter_title')): ?>
    <?php $viewingMessage = true ?>
    <h2><?php echo sfConfig::get('local_filter_title') ?></h2>
  <?php elseif($_site->displayGeneratedLocalTitle() && !$sf_request['iframe']): ?>
    <?php $viewingMessage = true ?>
    <?php if (isset($filters['profile']) && $filters['profile']): ?>
      <h2><strong><?php echo $filters['profile']['name'] ?></strong> events</h2>
    <?php elseif (isset($filters['venue']) && $filters['venue']): ?>
      <h2>Events at <strong><?php echo $filters['venue']['name'] ?></strong></h2>
    <?php elseif (isset($filters['event']) && $filters['event']): ?>
      <h2>All <strong><?php echo $filters['event']['name'] ?></strong> occurrences</h2>
    <?php else: ?>    
      <?php $type = isset($filters['event_type']) ? $filters['event_type'] : 'Events' ?>
      <?php $location = isset($filters['location']) ? $filters['location'] : 'Philadelphia' ?>
      <?php if ($type || $location): ?>
        <h2><?php echo $type ?: '' ?> <?php echo_if($type && $location, 'in') ?> <?php echo $location ?: '' ?></h2>
      <?php endif ?>
    <?php endif ?>
  <?php endif ?>
</div>
<?php sfConfig::set('has_viewing_message', $viewingMessage) ?>