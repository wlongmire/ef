<?php

class wfaActions extends BaseaActions
{
  public function executeError404(sfWebRequest $request)
	{
    sfContext::getInstance()->getLogger()->err('404');
  }
}