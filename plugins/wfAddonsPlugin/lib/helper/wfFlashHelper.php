<?php

/**
 * Echo the 'success', 'error' and 'notice' flashes
 */
function echoStandardFlashes()
{
  $user = sfContext::getInstance()->getUser();
  foreach(array('success', 'error', 'notice') as $flash)
  {
    if ($user->hasFlash($flash))
    {
      echo '<div class="' . $flash . ' flash">' . $user->getFlash($flash) . '</div>';
    }
  }
}
