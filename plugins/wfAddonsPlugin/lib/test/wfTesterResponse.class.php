<?php
class wfTesterResponse extends sfTesterResponse
{
  public function isSecure()
  {
    return $this->begin()
    ->isStatusCode(401)
    ->checkElement('h1', 'Sign In')
    ->end();    
  }
  
  public function hasListElements($count = 1)
  {
    return $this->checkElement('ul.shortList > li', $count);  
  }
  
  public function containsProfileTop(Profile $profile, $topText = false)
  {
    $tester = $this->begin()
      ->checkElement('.topProfile div.info p:first-child', $profile['username'])
      ->checkElement('.topProfile div.image a img[class="thumb"]');
    return $shortText == false ? $tester->end() :
      $tester->checkElement('.topProfile div.info h1', $topText)->end();
  }
  
  public function containsWorkout(Workout $workout, $template = 'top', $container = null)
  {
    if ($template == 'top')
    {
      return $this->checkElement($container . ' div.workoutShort h2 a', $workout['name']);
    }
    else if ($template == 'short')
    {
      if (is_null($container))
      {
        //FIXME: commented out because function was removed in [353]
        //$container = '#' . get_anchor_id_for_object($workout);
      }
      return $this->checkElement($container . ' .info h2 a', $workout['name']);
    }
    else
    {
      throw new sfException(sprintf('wfTesterResponse::containsWorkout has no way of checking template type "%s"', $template));
    }
  }
  
  public function containsHistory(History $history, $template = 'short')
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('History'));
    if ($template == 'short')
    {
      return $this->containsBarGraph($history);
    }
    else
    {
      throw new sfException(sprintf('wfTesterResponse::containsHistory has no way of checking template type "%s"', $template));
    }    
  }
  
  public function containsBarGraph(History $history, $baseSelector = null)
  {
    if (is_null($baseSelector))
    {
      //FIXME: commented out because function was removed in [353]
      //$baseSelector = '#' . get_anchor_id_for_object($history);
    }  
    $dateSelector = $baseSelector . ' div.bargraph p.date';
    $resultSelector = $baseSelector . ' div.bargraph p.result';
    return $this
      ->checkElement($resultSelector, (string)format_history($history))
      ->checkElement($dateSelector); 
  }
  
  public function isXmlList($objectArray = array())
  {
    $this->begin();
    foreach($objectArray as $object)
    {
      $this->checkElement(sprintf('ul li[%s_id="%s"]', strtolower(get_class($object)), $object['id']));
    }
    return $this->end();
  }
  
  public function hasBarGraphs($count)
  {
    return $this->checkElement('div.bargraph', $count); 
  }
  
  public function isHistoryList()
  {
    return $this->checkElement('div.historyList');
  }
  
  public function isHomePage()
  {
    return $this->checkElement('div.fullProfile');
  }
 
  public function isSignInPage()
  {
    return $this->checkElement('<h1>', 'Sign In')->checkElement('#sign_in_form');
  }
}