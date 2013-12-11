<?php

class myUser extends sfGuardSecurityUser
{
  /**
   * @return sfGuardUser|null
   */
  public function getProfile()
  {
    $gu = $this->getGuardUser();
    return $gu ? $gu->getProfile() : null;
  }  /**
   * Removes the attribute $name from the user's attributes
   * @param string $name The attribute name
   * @param mixed $default The default value to return, if $name isn't set
   * @param string $ns The attribute namespace
   * @return mixed The value of the attribute $name, if set, otherwise, $default
   */
  public function removeAttribute($name, $default = null, $ns = null)
  {
    return $this->attributeHolder->remove($name, $default, $ns);
  }
  
  /**
   * Overridden signIn to clear cached values/attributes
   */
  public function signIn($user, $remember = false, $con = null)
  {
    $this->setAttribute('user_id', $user->getId(), 'sfGuardSecurityUser');
    $this->setAuthenticated(true);

    // save last login
    $user->setLastLogin(date('Y-m-d H:i:s'));
    $user->save($con);

    // remember?
    if ($remember)
    {
      $expiration_age = sfConfig::get('app_sf_guard_plugin_remember_key_expiration_age', 15 * 24 * 3600);

      // remove old keys 
      Doctrine_Core::getTable('sfGuardRememberKey')->createQuery()
        ->delete()
        ->where('created_at < ?', date('Y-m-d H:i:s', time() - $expiration_age))
        ->execute();
      
      Doctrine_Core::getTable('sfGuardRememberKey')->createQuery()
        ->delete()
        ->where('user_id = ? AND ip_address = ?', array($user->getId(), $_SERVER['REMOTE_ADDR']))
        ->execute();

      // generate new keys
      $key = $this->generateRandomKey();

      // save key
      $rk = new sfGuardRememberKey();
      $rk->setRememberKey($key);
      $rk->setUser($user);
      $rk->setIpAddress($_SERVER['REMOTE_ADDR']);
      $rk->save($con);

      // make key as a cookie
      $remember_cookie = sfConfig::get('app_sf_guard_plugin_remember_cookie_name', 'sfRemember');
      sfContext::getInstance()->getResponse()->setCookie($remember_cookie, $key, time() + $expiration_age);
    }
    if ($this->getAttribute('redirect_on_auth'))
    {
      sfConfig::set('app_sf_guard_plugin_success_signin_url', $this->getAttribute('redirect_on_auth'));
      $this->removeAttribute('redirect_on_auth');
    }
  }

  /**
   * @return boolean True if sfGuardUser has been loaded 
   */
  public function guardUserExists()
  {
    return isset($this->user);
  }
}
