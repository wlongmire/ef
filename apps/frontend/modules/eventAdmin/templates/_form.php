<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<?php $form->debug() ?>
<div class="a-admin-form-container">
  <?php echo form_tag_for($form, '@event_admin', array('id'=>'a-admin-form', 'class'=> 'event')) ?>
    <?php echo $form->renderHiddenFields() ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <fieldset>
      <h3>Basics</h3>
      <?php foreach(array('name', 'media_item_id', 'venue_id', 'profiles_list') as $field): ?>
        <?php if (isset($form[$field])): ?>
          <?php echo $form[$field]->renderRow() ?>
        <?php endif ?>
      <?php endforeach ?>        
      <?php if (isset($form['owners_list']) || isset($form['is_published'])): ?>
        <div class="clear"></div>
        <h3 style="margin-top: 40px">Admin</h3>
        <?php foreach(array('is_published', 'owners_list') as $field): ?>
          <?php if (isset($form[$field])): ?>
            <?php echo $form[$field]->renderRow() ?>
          <?php endif ?>
        <?php endforeach ?> 
      <?php endif ?>      
            <div class="clear"></div>
      <h3 style="clear: both; margin-top: 40px">Categorize</h3>
      <?php foreach(array('event_type_id', 'tags', 'disciplines_list') as $field): ?>
        <?php if (isset($form[$field])): ?>
          <?php echo $form[$field]->renderRow() ?>
        <?php endif ?>
      <?php endforeach ?>       
            <div class="clear"></div>
      <h3 style="clear: both; margin-top: 40px">Details</h3>      
      <?php foreach(array('url', 'ticket_url', 'min_cost', 'blurb',) as $field): ?>
        <?php if (isset($form[$field])): ?>
          <?php if ($field == 'min_cost'): ?>
            <div class="a-form-row cost">
              <label>Cost</label>
              <div class="a-form-field">
                <?php echo $form['min_cost']->render() ?>
                <span style="clear: none; line-height: 20px; margin: 0 5px;">to</span>
                <?php echo $form['max_cost']->render() ?>
                <?php echo $form['min_cost']->renderError() ?>
                <?php echo $form['max_cost']->renderError() ?>
              </div>
            </div>      
          <?php else: ?>
            <?php echo $form[$field]->renderRow() ?>
          <?php endif ?>
        <?php endif ?>
      <?php endforeach ?>          
      <div class="clear"></div>
      <div class="date-entry">
        <h3 style="margin-top: 40px">Dates</h3>
        <?php if (isset($form['date_mode'])): ?>
          <?php echo $form['date_mode']->renderRow() ?>
        <?php endif ?>
        <?php if (isset($form['event_recurrance_object'])): ?>
          <?php echo $form['event_recurrance_object']->renderRow() ?>
        <?php endif ?>
        <div class="a-form-row event-occurances <?php echo_if($form->isNew(), 'hide') ?>">
          <div class="a-form-field occurance-count clearfix">
            <?php echo $form['event_occurance_count']->render() ?>
            <?php echo $form['event_occurance_count']->renderError() ?>
          </div>
          <div class="a-form-field occurance-list">
            <?php $count = 0 ?>
            <?php foreach($form['event_occurance_objects'] as $formFieldSchema): ?>
              <div class="event-occurance clearfix <?php echo parity(++$count) ?>">
                <div class="start-date">
                  <?php echo $formFieldSchema['start_date']->render() ?>
                </div>
                <div class="end-date">
                  to
                  <?php echo $formFieldSchema['end_date']->render() ?>                
                </div>
                <div class="start-time">
                  starting at
                  <?php echo $formFieldSchema['start_time']->render() ?>
                </div>
                <div class="end-time">
                  until
                  <?php echo $formFieldSchema['end_time']->render() ?>
                </div>
                <?php if (!$formFieldSchema['ticket_url']->getValue()): ?>
                  <div class="add-ticket-url">
                    <a href="javascript:;" class="add-ticket-url">Add Ticket URL</a>
                  </div>
                <?php endif ?>
                <?php echo $formFieldSchema['start_date']->renderError() ?>
                <?php echo $formFieldSchema['end_date']->renderError() ?>
                <?php echo $formFieldSchema['start_time']->renderError() ?>
                <?php echo $formFieldSchema['end_time']->renderError() ?>
                <div class="ticket-url <?php echo_if(!$formFieldSchema['ticket_url']->getValue(), 'hide') ?>">
                  <?php echo $formFieldSchema['ticket_url']->renderLabel('Ticket URL') ?>
                  <?php echo $formFieldSchema['ticket_url']->render() ?>
                  <?php echo $formFieldSchema['ticket_url']->renderError() ?>
                </div>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>
    </fieldset>

    <?php include_partial('eventAdmin/form_actions', array('event' => $event, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
