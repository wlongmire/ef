<?php use_helper('a') ?>
<?php slot('body_class') ?>a-default<?php end_slot() ?>
<?php if ($sf_request->isXmlHttpRequest()): ?>
  <div>
    <a href="javascript:;" class="show-listings">Return to listings</a>
    <br/>
    <br/>
  </div>
<?php endif ?>
<h1 class="page-title"><?php echo $page->getTitle() ?></h1>
<?php a_area('body', array(
	'allowed_types' => array(
		'aRichText', 
		'aVideo',		
		'aSlideshow',
		'aFile',
		'aButton',
		'aRawHTML',
	),
  'type_options' => array(
		'aRichText' => array(
		  'tool' => 'Main',
			// 'allowed-tags' => array(),
			// 'allowed-attributes' => array('a' => array('href', 'name', 'target'),'img' => array('src')),
			// 'allowed-styles' => array('color','font-weight','font-style'), 
		), 	
		'aVideo' => array(
			'width' => 480, 
			'height' => false, 
			'resizeType' => 's',
			'flexHeight' => true, 
			'title' => false,
			'description' => false,			
		),		
		'aSlideshow' => array(
			'width' => 640, 
			'height' => false,
			'resizeType' => 's',  
			'flexHeight' => true, 
			'constraints' => array('minimum-width' => 480),
			'arrows' => true,
			'interval' => false,			
			'random' => false, 
			'title' => false,
			'description' => false,
			'credit' => false,
			'position' => false,
			'itemTemplate' => 'slideshowItem',       			
		),
		'aFile' => array(
		), 
		'aButton' => array(
			'width' => 480, 
			'flexHeight' => true, 
			'resizeType' => 's', 
			'constraints' => array('minimum-width' => 480),  
			'rollover' => true, 
			'title' => true, 
			'description' => false
		),		
		'aRawHTML' => array(
		), 
))) ?>