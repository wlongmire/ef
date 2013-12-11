<?php
class wfBasicSecurityFilter extends sfBasicSecurityFilter
{
  /**
   * Executes this filter. Extended to set redirect_on_auth attribute.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    // disable security on login and secure actions
    $request = $this->context->getRequest();
    if ($this->context->getActionStack()->getSize() == 1 && $request->isMethod('get') && !$request->isXmlHttpRequest() && !$this->context->getUser()->isAuthenticated() &&
      (
      (sfConfig::get('sf_login_module') == $this->context->getModuleName()) && (sfConfig::get('sf_login_action') == $this->context->getActionName())
      ||
      (sfConfig::get('sf_secure_module') == $this->context->getModuleName()) && (sfConfig::get('sf_secure_action') == $this->context->getActionName())
    ))
    {
      //set referer if we hit the login or secure module directly
      $this->context->getUser()->setAttribute('redirect_on_auth', $this->context->getRequest()->getReferer());
    }

    if ($this->isSecureAction())
    {
      parent::execute($filterChain);
    }
    else
    {
      $filterChain->execute();
    }
  }

  /**
   * Extended to set referer as user attribute
   * @throws sfStopException
   */
  protected function forwardToLoginAction()
  {
    $this->context->getUser()->setAttribute('redirect_on_auth', $this->context->getRequest()->getUri());
    parent::forwardToLoginAction();
  }

  /**
   * Returns whether the action is secure.
   * 
   * @return boolean
   */
  protected function isSecureAction()
  {
    return $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance()->isSecure();
  }
}
