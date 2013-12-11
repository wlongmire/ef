<div class="header clearfix" id="header">
  <?php if ($_site->displayAuthentication() && $sf_user->isAuthenticated()): ?>
    <div class="login">
      <?php echo link_to($sf_user->getGuardUser()->getEmailAddress(), 'profile_edit', array('tab' => 'basic info')) ?>
      |
      <?php echo link_to('Sign Out', 'sf_guard_signout') ?>
    </div>
  <?php endif ?>  
  <?php $logo = $_site->logo_id ? $_site->Logo : null ?>
  <?php $dynamicNav = $_site->useDynamicNavigation() ?>
  <?php if ($logo || $dynamicNav): ?>
    <table>
      <tr>
        <?php if ($logo): ?>
          <td class="branding">
            <a href="<?php echo $_site->getReturnUrl() ?: url_for('homepage') ?>" class="logo no-text">
              <img src="<?php echo $logo->getRelativeOriginalPath() ?>" alt="<?php echo $_site->org_name  ?>"/>
            </a>        
          </td>
        <?php endif ?>

        <?php if ($dynamicNav): ?>
          <td class="tagline" <?php echo_if(!$logo, 'style="padding-left: 12px; padding-top: 7px; font-size: 18px;"') ?>>
            <?php //tagline serves as default title as well, see CommonHelper ?>
            <?php a_slot('tagline', 'aText', array(
                'slug' => $_site->getGlobalVirtualPageSlug(),
                'area_class' => 'tagline',
                'multiline' => false
            )) ?>      
          </td>
        <?php endif ?>
      </tr>
    </table>
  <?php endif ?>
  <?php if ($_site->useDynamicNavigation()): ?>
    <?php if (!has_slot('local-nav')): ?>
      <?php include_component('site', 'navigation') ?>
    <?php else: ?>
      <?php include_slot('local-nav') ?>
    <?php endif ?>  
  <?php elseif ($_site->displayCmsNavigation()): ?>
    <?php include_component('aNavigation', 'tabs', array(
        'root' => '/', 
        'name' => 'main', 
        'draggable' => false, 
        'dragIcon' => false
    )) ?>
  <?php endif ?>
</div>