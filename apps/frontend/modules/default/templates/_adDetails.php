<?php $commonOptions = array(
    'slug' => $_site->name . ($_site->displaySiteWideAds() ? 
                  '' : 
                  '-' . wfToolkit::hash(serialize($sf_user->getAttribute($attribute)))
              ),
    'edit' => $sf_user->hasCredential('admin'),
    'history' => false
) ?>
<?php a_slot('details-ads-header', 'aText', $commonOptions) ?>
<?php a_area('details-ads', $commonOptions + array(
          'allowed_types' => array('aButton'),
          'type_options' => array(
              'aButton' => array(
                  'width' => 225,
                  'constraints' => array('minimum-width' => 225),
                  'flexHeight' => true,
                  'height' => false,
                  'rollover' => true, 
                  'delete' => true,
                  'title' => false, 
                  'description' => false,
                  'newWindow' => true
              )
          ),
          'areaLabel' => 'Add Advertisement',
          'displayRandomSlot' => true
      )) ?>