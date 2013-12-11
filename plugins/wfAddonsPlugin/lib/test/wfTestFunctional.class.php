<?php
class wfTestFunctional extends sfTestFunctional
{
  private $testGroup = null;
  private $groupNumber = 0;
  private $testNumber = 0;

  public function __construct(sfBrowserBase $browser, lime_test $lime = null, $testers = array())
  {
    $testers = array_merge(array(
      'form' => 'wfTesterForm',
      'request' => 'wfTesterRequest',
      'response' => 'wfTesterResponse',
      'doctrine' => 'sfTesterDoctrine'
    ), $testers);

    parent::__construct($browser, $lime, $testers);
  }

  public function info($string)
  {
    return parent::info(sprintf('%s.%s - %s', $this->groupNumber, ++$this->testNumber, $string));
  }

  public function setInfoGroup($string)
  {
    $this->testNumber = 0;
    return parent::info(sprintf('%s: %s', ++$this->groupNumber, $string));
  }

  /**
   * Sign in
   *
   * @param string $username
   * @param string $password
   * 
   * @return wfTestFunctional The current instance
   */
  public  function signin($username, $password) {
    return $this->
      info(sprintf('Signing in using username "%s" and password "%s"', $username, $password))->
      sslNextRequest()->
      getAndCheck('sfGuardAuth', 'signin', '/login', 401)->
      click('Sign In!', array('signin' => array('username' => $username, 'password' => $password)))->
      with('response')->begin()->
        isRedirected()->
      end()

      // this should be part of the test, but following redirects is broken in symfony (i think).
      // see http://trac.symfony-project.org/ticket/5459
//      ->followRedirect()->
//      with('response')->begin()->
//        isStatusCode(200)->
//      end()
    ;
  }

  /**
   * Same as post() but performs the post as an xmlHttpRequest
   *
   * @param string $uri         The URI to fetch
   * @param array  $parameters  The Request parameters
   * @param bool   $changeStack  Change the browser history stack?
   *
   * @return wfTestFunctional The current instance
   */
  public function xmlHttp($uri, $parameters = array(), $changeStack = true)
  {
    $this->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
    return parent::post($uri, $parameters, $changeStack);
  }

  /**
   * Makes a request and verifies that the basics (module, action, method and status code) are correct.
   * @see sfTestFunctionalBase::getAndCheck()
   *
   * @param  string $method      The method to use (GET, POST, or XMLHTTP)
   * @param  string $module      Module name
   * @param  string $action      Action name
   * @param  string $url         Url
   * @param  string $code        The expected return status code
   * @param  array  $parameters  Request parameters
   *
   * @return wfTestFunctional The current instance
   */
  public function callAndCheck($method, $module, $action, $url = null, $code = 200, $parameters = array())
  {
    switch($method)
    {
      case 'XMLHTTP':
        $function = 'xmlHttp';
        $methodForTest = 'POST';
        break;

      case 'GET':
      case 'POST':
        $function = strtolower($method);
        $methodForTest = $method;
        break;

      default:
        throw new InvalidArgumentException('The ' . $method . ' method is not supported');
    }

    if (isset($parameters['sf_method']))
    {
      $methodForTest = strtoupper($parameters['sf_method']);
    }

    return $this->
      $function(null !== $url ? $url : sprintf('/%s/%s', $module, $action), $parameters)->
      with('request')->begin()->
        isMethod($methodForTest)->
        isParameter('module', $module)->
        isParameter('action', $action)->
      end()->
      with('response')->isStatusCode($code)
    ;
  }

  public function getTestUser()
  {
    return Doctrine::getTable('Profile')->findOneByUsername('jeremy');
  }

  public function getTestUserPassword()
  {
    return 'password';
  }

  public function getAnchor()
  {
    $location = $this->browser->getRequest()->getUri();
    $tokens = explode('#', $location);
    if (count($tokens) !== 2)
    {
      throw new sfException(sprintf('Unable to get anchor from location "%"', $location));
    }
    return $tokens[1];
  }

  public function generateString($characters = 0, $lines = 0)
  {
    $string = '';
    for($i = 0; $i < $characters; $i++)
    {
      $string .= 'a';
    }
    for($i = 0; $i < $lines; $i++)
    {
      $string .= "\n";
    }
    return $string;
  }

  public function generateDate($date, $beforeOrAfter = 'after')
  {
    if ($date instanceof DateTime == false)
    {
      $date = new DateTime($date);
    }
    if ($beforeOrAfter == 'after')
    {
      $date->modify('+1 day');
    }
    else
    {
     $date->modify('-1 day');
    }
    return $date->format('m/d/Y');
  }
}
?>