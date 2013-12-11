<?php $page = aTools::getCurrentPage() ?>
<?php $isHomePage = $sf_context->getRouting()->getCurrentRouteName() == 'homepage' ?>
<?php $isAdmin = $sf_user->hasCredential('admin') ?>
<?php if ($isAdmin): ?>
  <?php $buttons = array() ?>
  <?php $buttons[] = a_link_button('Events', 'event_admin', array(), array('icon', 'alt', 'no-bg', 'a-events')) ?>
  <?php $buttons[] = a_link_button('Blog', 'a_blog_admin', array(), array('icon', 'alt', 'no-bg', 'a-blog')) ?>
  <?php $buttons[] = a_link_button('Venues', 'venue_admin', array(), array('alt', 'no-bg')) ?>
  <?php $buttons[] = a_link_button('Profiles', 'profile_admin', array(), array('icon', 'alt', 'no-bg', 'a-users')) ?>
  <?php $buttons[] = a_link_button('Media', 'aMedia/index', array(), array('icon', 'alt', 'no-bg', 'a-media')) ?>
  <?php $buttons[] = a_link_button('Locations', 'location_admin', array(), array('alt', 'no-bg')) ?>
  <?php $buttons[] = a_link_button('Disciplines', 'discipline_admin', array(), array('alt', 'no-bg')) ?>
  <?php $buttons[] = a_link_button('Accounts', 'user_admin', array(), array('alt', 'no-bg')) ?>
  <?php $buttons[] = a_link_button('Tags', 'tag_admin', array(), array('alt', 'no-bg')) ?>
  <?php if ($_site->allowReorganize()): ?>
    <?php $buttons[] = a_link_button('Reorg', 'a/reorganize', array(), array('icon', 'alt', 'no-bg', 'a-reorganize')) ?>
  <?php endif ?>
  <?php if ($_site->useDynamicNavigation()): ?>
    <?php $buttons[] = a_link_button($_site->org_name . ' Nav', 'site_navigation', array(), array('alt', 'no-bg', 'a-reorganize', 'icon')) ?>
  <?php endif ?>
  <?php if ($sf_user->isSuperadmin()): ?>
    <?php $buttons[] = a_link_button('Site Admin', 'site_admin', array(), array('alt', 'no-bg')) ?>
  <?php endif ?>
  <div class="a-ui a-global-toolbar clearfix">
    <div class="toolbar-controls clearfix">
      <ul class="a-ui a-controls">
        <?php if ($isAdmin && $page && !$page->admin): ?> 	
          <li class="ui-widget">
            <a href="/#page-settings" onclick="return false;" class="a-btn icon alt no-bg a-page-settings" id="a-page-settings-button"><span class="icon"></span>Page Settings</a>
            <div id="a-page-settings" class="a-page-settings-menu dropshadow"></div>
          </li>				
          <?php a_js_call('apostrophe.enablePageSettingsButtons(?)', array(
              'aPageSettingsURL' => url_for('a/settings') . '?' . http_build_query(array('id' => $page->id)), 
              'aPageSettingsCreateURL' => url_for('a/settings') . '?' . http_build_query(array('new' => 1, 'parent' => $page->slug))
          )) ?>
        <?php endif ?>  	
        <?php if ($buttons): ?>
          <li><?php echo implode('</li><li>', $buttons) ?></li>
        <?php endif ?>
        <?php if ($isAdmin && $page): ?>
          <li class="ui-widget">
            <?php // Triggers the same form as page settings now ?>
            <a href="/#add-page" class="a-btn icon a-add a-create-page" id="a-create-page-button" onclick="return false;"><span class="icon"></span>Add Page</a>
            <div id="a-create-page" class="a-page-settings-menu dropshadow"></div>
          </li>
        <?php endif ?>        
      </ul>
    </div>
  </div>
<?php endif ?>

<?php if ($isAdmin): ?>
	<?php include_partial('a/historyBrowser') ?>
	<div class="a-page-overlay"></div>
	<?php if ($isHomePage): ?>
    <?php if (!$page): ?>
      <?php $page = aPageTable::retrieveBySlug('/') ?>
    <?php endif ?>
		<?php a_js_call('apostrophe.enablePageSettingsButtons(?)', array('aPageSettingsURL' => url_for('a/settings') . '?' . http_build_query(array('id' => $page->id)), 'aPageSettingsCreateURL' => url_for('a/settings') . '?' . http_build_query(array('new' => 1, 'parent' => $page->slug)))) ?>
	<?php endif ?>	
<?php endif ?>