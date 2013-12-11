<?php use_stylesheet('page/profile_edit.less') ?>
<?php include_partial('user/accountHeader', array(
    'user' => $sf_user->getGuardUser(),
    'tab' => $sf_request['tab']
)) ?>
<h2 class="page-title"><?php echo ucwords($sf_request['tab']) ?></h2>
<?php include_partial('default/flashes') ?>
<?php a_slot('profile-edit-info', 'aRichText', array(
    'slug' => 'profile_edit_sharing'
)) ?>
<div class="profile-sharing">
  <?php if ($isNew): ?>
    <p>After you finish creating your profile, you will be able to share your profile and events.</p>
  <?php else: ?>  
    <h3>Your Profile & Events Link</h3>
    <p>Use this URL to share a link to your profile and events.</p>
    <input type="text" class="a-select-on-focus profile-url" 
           value="<?php echo url_for('event_toggle_filter', array('filter' => 'profile', 'value' => $profile['id'], 'label' => myTools::urlify($profile['name'])), true) ?>"/>                 
    <?php a_js_call('apostrophe.selectOnFocus(?)', '.a-select-on-focus') ?>
    <?php if ($profile->is_group): ?>
      <h3>Your Members Link</h3>
      <p>Use this URL to share a link to your group profile and group members.</p>
        <h3>Your Profile Link</h3>
        <input type="text" class="a-select-on-focus profile-url" value="<?php echo url_for('profile_toggle_filter', 
             array('filter' => 'profile', 'value' => $profile['id'], 'label' => myTools::urlify($profile['name'])), true) ?>"/>
    <?php endif ?>
  <?php endif ?>
</div>

