<?php
function event_cost($event)
{
  $parts = array();
  $fields = $event['min_cost'] == $event['max_cost'] ? array('min_cost') : array('min_cost', 'max_cost');
  foreach($fields as $costField)
  {
    if ($event[$costField] !== null)
    {
      $parts[] = '<span class="cost">' . ($event[$costField] > 0 ? '$' . round($event[$costField]) : 'Free') . '</span>';
    }
  }
  return $parts ? '<span class="cost-range">' . implode('<span class="help-text">&ndash;</span>', $parts) . '</span>' : '';
}

function event_date($date)
{
  $dt = $date instanceof DateTime ? $date : new DateTime($date);
  $prefix;
  
  if ($dt->format('Y-m-d') == date('Y-m-d'))
  {
    $prefix = 'Today';
  }
  elseif ($dt->format('Y-m-d') == date('Y-m-d', strtotime('+1 day')))
  {
    $prefix = 'Tomorrow';
  }
  else
  {
    $prefix = $dt->format('l');
  }
  return $prefix . ', ' . $dt->format('M d, Y');
}

function map_image_url($address, $options = array())
{
  $options = array_merge(array(
    'zoom' =>  15,
    'marker' => true,
    'width' => 0,
    'height' => 0,  
    'mapType' =>  'roadmap', //http://code.google.com/apis/maps/documentation/staticmaps/#MapTypes
  ), $options);
  
  $url = 'http://maps.google.com/maps/api/staticmap?sensor=false&center=%location%&zoom=%zoom%&size=%width%x%height%&maptype=%mapType%';
  if ($options['marker'])
  {
    $url .= '&markers=color:red|%location%';
  }
  return strtr($url, array(
    '%location%' => urlencode($address),
    '%width%'    => $options['width'],
    '%height%'   => $options['height'],
    '%marker%'   => $options['marker'],
    '%mapType%'  => $options['mapType'],
    '%zoom%'     => $options['zoom']
  ));
}

function map_url($address, $options = array())
{
  $options = array_merge(array(
      'type' => 'm',
      'zoom' => 16 //one more than default for image url
  ), $options);
  
  return strtr('http://maps.google.com/maps?q=%location%&t=%type%&z=%zoom%', array(
    '%location%' => urlencode($address),
    '%type%' => $options['type'],
    '%zoom%'     => $options['zoom']
  ));
}

function ob_callback_no_intratag_whitespace($buffer, $mode)
{
  return preg_replace('/>\s+</', '><', $buffer);
}

function css_color($color)
{
  if ($color[0] == '#')
  {
    $color = substr($color, 1);
  }
  $length = strlen($color);  
  if (ctype_xdigit($color) && ($length == 3 || $length == 6))
  {
    return '#' . $color;
  }
  return 'inherit';
}

function expandable_blurb($blurb, $word_limit = 60)
{
  $excerpt = aHtml::limitWords($blurb, $word_limit, array('append_ellipsis' => true));
  $isTruncated = $excerpt != $blurb;
  if ($isTruncated)
  {
    $pos = strrpos($excerpt, '&hellip;');
    $replace = '&hellip; ' . content_tag('a', 'Read More', array('href' => 'javascript:;', 'class' => 'blurb-expand'));
    $excerpt = $pos === false ? $excerpt : substr_replace($excerpt, $replace, $pos, 8); //8 = strlen('&hellip;')
    echo content_tag('div', $blurb, array('class' => 'blurb full section'));
  }
  echo content_tag('div', $excerpt, array('class' => 'blurb section'));  
}

function my_a_button($label, $url, $classes = array(), $options = array())
{
  $options['href'] = $url;
  _my_a_button_common($label, $classes, $options);
  return content_tag('a', $label, $options);
}

function my_a_js_button($label, $classes = array(), $options = array())
{
  return my_a_button($label, 'javascript:;', $classes, $options);
}

function my_a_link_button($label, $route, $params = array(), $classes = array(), $options = array())
{
  _my_a_button_common($label, $classes, $options);
  return link_to($label, $route, $params, $options);
}

function _my_a_button_common(&$label, &$classes, &$options)
{
  if (in_array('flag', $classes))
  {
    if (!$label && $options['title'])
    {
      $label = $options['title'];
    }
    if (!in_array('no-label', $classes))
    {
      $classes[] = 'no-label';
    }
    unset($options['title']);
    $label = '<span class="flag-label">' . $label . '</span>';
  }

  if (in_array('icon', $classes))
  {
    $label = '<span class="icon"></span>' . $label;
  }

  if (in_array('a-events', $classes)) // if it's an a-events button, grab the date and append it as a class
  {
		$classes[] = 'day-'.date('j');
	}

	if (!in_array('a-link', $classes) && !in_array('a-arrow-btn', $classes) && !in_array('a-btn', $classes)) //it must be one of the three
  {
	  $classes[] = 'a-btn';
	}

  $options['class'] = implode(' ', $classes);
}


function get_default_title(Site $site)
{
  aTools::globalSetup(array('type' => 'aText', 'global' => false, 'slug' => $site->getGlobalVirtualPageSlug(), 'singleton' => true));
  $page = aTools::getCurrentPage();
  $slot = $page->getSlot('tagline');
  if ($slot && $slot->type == 'aText')
  {
    $text = strip_tags($slot->value);
  }
  else
  {
    $text = 'Find Events & Profiles';
  }

  aTools::globalShutdown();

  return $text;
}
