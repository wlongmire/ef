<div class="a-admin-list">
  <?php $isAdmin = $sf_user->hasCredential('admin') && false ?>
  <?php if (!$pager->getNbResults()): ?>
    <p><?php echo __('No result', array(), 'apostrophe') ?></p>
  <?php else: ?>
    <?php if (false): ?>
      <table class="filter-data event fixed-columns no-time-or-cost with-images">
        <?php $row = 0 ?>
        <?php foreach($pager->getResults() as $event): ?>
          <tr class="<?php echo parity(++$row) ?>">
            <?php $item = $event['Picture'] ?>              
            <td class="image">
              <?php if ($item && $item['type'] == 'image'): ?>
                <a class="details">
                  <?php include_partial('aMedia/imageForList', array(
                      'item' => $item
                  )) ?>
                </a>
              <?php endif ?>
            </td>            
            <td class="event standard">
              <?php echo link_to($event['name'], 'event_admin_edit', $event) ?>
              <?php foreach($event['Profiles'] as $profile): ?>
                <br/>
                <?php if ($isAdmin): ?>
                  <?php echo link_to($profile['name'], 'profile_admin_edit', $profile) ?>
                <?php else: ?>
                  <?php echo $profile['name'] ?>
                <?php endif ?>
              <?php endforeach ?>
            </td>    
            <td class="location standard">
              <?php if (isset($event['Disciplines'])): ?>
                <?php echo implode(', ', wfToolkit::arrayPluck($event['Disciplines'], 'name')) ?>
                <br/>
              <?php endif ?>           
              <?php if (isset($event['Venue'])): ?> 
                <strong><?php echo $event['Venue']['name'] ?></strong>
                <br/>
                <?php echo $event['Venue']['Location']['name'] ?>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </table>    
    <?php else: ?>
      <table cellspacing="0" class="a-admin-list-table">
        <thead>
          <tr>
                        <?php include_partial('eventAdmin/list_th_tabular', array('sort' => $sort, 'helper' => $helper)) ?>
                        <th id="a-admin-list-th-actions"><?php echo __('Actions', array(), 'apostrophe') ?></th>
                    </tr>
        </thead>
        <tfoot>
          <tr>
            <th colspan="9">

            </th>
          </tr>
        </tfoot>
        <tbody>
          <?php $n=1; $total = $pager->getNbResults(); foreach ($pager->getResults() as $i => $event): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
            <tr class="a-admin-row <?php echo $odd ?> <?php echo ($n == $total)? 'last':'' ?> <?php echo ($n == 1)? 'first':'' ?>">
                            <?php include_partial('eventAdmin/list_td_tabular', array('event' => $event, 'helper' => $helper)) ?>
                            <?php include_partial('eventAdmin/list_td_actions', array('event' => $event, 'helper' => $helper)) ?>
                        </tr>
          <?php $n++; endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  <?php endif ?>
  <h6 class="a-admin-list-results">
    <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'apostrophe') ?>
  </h6>
  <?php if ($pager->haveToPaginate()): ?>
    <?php include_partial('eventAdmin/pagination', array('pager' => $pager)) ?>
  <?php endif; ?>	    
</div>