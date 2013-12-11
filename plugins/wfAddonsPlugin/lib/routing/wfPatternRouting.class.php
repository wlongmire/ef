<?php
class wfPatternRouting extends sfPatternRouting
{
  public function absolutizeUrl($url)
  {
    if (isset($this->options['context']['host']) && 0 !== strpos($url, 'http'))
    {
      $url = 'http'.(isset($this->options['context']['is_secure']) && $this->options['context']['is_secure'] ? 's' : '').'://'.$this->options['context']['host'].$url;
    }
    return $url;
  }

  public function getRoutePattern($name)
  {
    return $this->routes[$name]->getPattern();
  }
  
  public function generateRouteTemplate($name, $params = array(), $absolute = false)
  {
    $route = $this->routes[$name];
    $variables = $route->getVariables();
    $url = $route->getPattern();

    uasort($variables, create_function('$a, $b', 'return strlen($a) < strlen($b);')); //prevents overlapping variable names being replaced
    foreach ($variables as $variable => $value)
    {
      if (isset($params[$variable]))
      {
        $url = str_replace($value, urlencode($params[$variable]), $url);
      }
      else if ($variable != 'sf_format') //ignore sf_format for templates
      {
        $url = str_replace($value, sprintf('#{%s}', $variable), $url);
      }
      else
      {
        $defaults = $route->getDefaults();
        if (isset($defaults['sf_format']) && !is_array($defaults['sf_format']))
        {
          $url = str_replace($value, $defaults['sf_format'], $url);
        }
      }
    }

    /*attach extra params as get params if extra_params_as_query_string is true*/
    if (wfToolkit::offsetGet($route->getOptions(), 'extra_parameters_as_query_string') &&
          //any key in $params not in defaultParams, defaults, or variables is an extra parameter
          $extras = array_diff_key($params, $route->getDefaultParameters(), $route->getDefaults(), $variables))
    {
      $url .= '?' . http_build_query($extras);
    }
    
    return $this->fixGeneratedUrl($url, $absolute);
  }
  
  /**
   * Extends sfPatternRouting to remove trailing '/' characters when generating long URLs (http://trac.symfony-project.org/ticket/6722)
   * @see sfPatternRouting::generate($name, $params, $absolute)
   */
  public function generate($name, $params = array(), $absolute = false)
  {
    if (isset($params['_anchor']))
    {
      $anchor = $params['_anchor'];
      unset($params['_anchor']);
    }
  	$url = parent::generate($name, $params, $absolute);
  	if ($url[strlen($url) - 1] == '/' && strlen($url) > 1)
  	{
      $url = substr($url, 0, strlen($url) - 1); 		
  	}
    if (isset($anchor))
    {
      $url .= $anchor;
    }
  	return $url;
  }  
}
