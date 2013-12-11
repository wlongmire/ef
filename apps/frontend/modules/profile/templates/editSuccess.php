<?php use_stylesheet('page/profile_edit.less') ?>
<?php include_partial('user/accountHeader', array(
    'user' => $sf_user->getGuardUser(),
    'tab' => $sf_request['tab']
)) ?>
<h2 class="page-title"><?php echo ucwords($sf_request['tab']) ?></h2>
<?php include_partial('default/flashes') ?>
<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>
<form class="standard-form validate profile-form clearfix" method="post" enctype="multipart/form-data"
      action="<?php echo url_for('profile_edit', array('tab' => $sf_request['tab'])) ?>">
    <?php $form->debug() ?>  
    <fieldset>
      <?php echo $form->renderGlobalErrors() ?>
      <?php foreach($form as $name => $formField): ?>
        <?php if ($formField->isHidden()) { continue; }?>
        <?php if ($name == 'disciplines_list'): ?>
          <div class="a-form-row">
            <?php a_slot('discipline-help', 'aRichText', array(
              'slug' => 'profile_edit_work',
              'area_class' => 'full'
            )) ?>          
          </div>
        <?php endif ?>    
        <?php echo $formField->renderRow() ?>
      <?php endforeach ?>
    </fieldset>
    <div class="a-form-row submit">
      <ul class="a-ui a-controls">  
        <li>
          <?php echo $form->renderHiddenFields() ?>
          <?php echo a_submit_button($submitLabel, array('big')) ?>
        </li>
      </ul>
    </div>
</form>
