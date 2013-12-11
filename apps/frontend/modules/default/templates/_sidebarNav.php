<ul class="a-controls a-ui stacked separate">
  <?php $buttons = array() ?>
  <?php if (!$sf_user->isAuthenticated()): ?>
    <?php $buttons[] = my_a_link_button('Sign In', 'sf_guard_signin', array(), array('big')) ?>
    <?php $buttons[] = my_a_link_button('Create Account / Profile', 'join', array(), array('big')) ?>
  <?php else: ?>
    <li>
      <h4>Welcome, <?php echo link_to($sf_user->getGuardUser()->full_name, 'user_edit') ?>.</h4>
      <?php $roles = array() ?>
      <?php foreach(array('admin', 'site_admin', 'venue_manage') as $candidate): ?>
        <?php if ($sf_user->hasCredential($candidate)): ?>
          <?php $roles[] = str_replace('_', ' ', $candidate) ?>
        <?php endif ?>
      <?php endforeach ?>
      <?php if ($roles): ?>
        <div class="help standard">
          <?php echo pluralize('Role', count($roles)) ?>: <?php echo implode(', ', $roles) ?>.
        </div>
      <?php endif ?>
          
    </li>
    <?php $buttons[] = my_a_link_button('Your Events', 'event_admin', array(), array('icon', 'big', 'a-events')) ?>  
    <?php if ($sf_user->hasCredential('venue_manage') || $sf_user->hasGroup('admin')): ?>
      <?php $buttons[] = my_a_link_button('Your Venues', 'venue_admin', array(), array('big', 'venue', 'icon')) ?>
      <?php $buttons[] = my_a_link_button($sf_user->hasCredential('admin') ? 'All Profiles' : 'Your Profiles', 'profile_admin', array(), array('icon', 'big', 'a-users')) ?>        
    <?php endif ?>
    <?php if (false): ?>
      <?php $buttons[] = my_a_link_button('Search Profiles', 'profile_search', array(), array('big', 'icon', 'a-search')) ?>   
    <?php endif ?>
    <?php $buttons[] = my_a_link_button('Personal Profile', 'profile_edit', array('tab' => 'basic info'), array('big', 'icon', 'a-edit')) ?>  
    <?php $buttons[] = my_a_link_button('Edit Account', 'user_edit', array(), array('icon', 'alt', 'a-edit')) ?>
    <?php $buttons[] = my_a_link_button('Sign out', 'sf_guard_signout', array(), array('alt')) ?>
  <?php endif ?>
  <li><?php echo implode('</li><li>', $buttons) ?></li>
</ul>