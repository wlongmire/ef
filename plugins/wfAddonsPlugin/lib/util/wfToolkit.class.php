<?php

class wfToolkit
{

  /**
   * Iterates through $array and collects $key values.
   * Emulates Prototype.js's Enumerable.pluck
   * If $key is not set on $item, that item will be skipped, so the size of the array returned by arrayPluck may not match
   * the original array size. If $key does not exist at all, an empty array will be returned.
   * @param $array
   * @param $key
   * @param array $options:
   * * maintain_keys: keep the keys of the original array (default: false)
   * @return array
   */
  public static function arrayPluck($array, $key, $options = array())
  {
    $options = array_merge(array(
        'maintain_keys' => false
    ), $options);
    $result = array();
    foreach ($array as $index => $item)
    {
      if (isset($item[$key]))
      {
        if ($options['maintain_keys'])
        {
          $result[$index] = $item[$key];
        }
        else
        {
          $result[] = $item[$key];
        }
      }
    }
    return $result;
  }

  public static function arrayFirst($array)
  {
    return is_array($array) ? reset($array) : null;
  }

  public static function arrayLast($array)
  {
    return is_array($array) ? end($array) : null;
  }

  /**
   * Flattens the array in-order.
   * @param array $array
   * @param boolean $preserve True to preserve keys that are strings. This means earlier values with the same key as later values will be overwritten.
   * @return array
   */
  public static function arrayFlatten($array, $maxDepth = null, $preserve = false)
  {
    if ($maxDepth === 0)
    {
      return $array;
    }
    $r = array();
    foreach ($array as $key => $value)
    {
      if (is_array($value))
      {
        $r = array_merge($r, wfToolkit::arrayFlatten($value, ($maxDepth === null ? null : $maxDepth - 1), $preserve));
      }
      else if ($preserve && is_string($key))
      {
        $r[$key] = $value;
      }
      else
      {
        $r[] = $value;
      }
    }
    return $r;
  }

  /**
   * @param array $array
   * @param callback $callback
   * @return mixed Returns the first element for which the callback is true
   * @see array_filter
   */
  public static function arrayFind($array, $callback = null)
  {
    $results = $callback === null ?
               array_filter($array) :
               array_filter($array, $callback);

    return $results ? wfToolkit::arrayFirst($results) : null;
  }

  public static function offsetGet($array, $offset)
  {
    return isset($array[$offset]) ? $array[$offset] : null;
  }

  /**
   * wfToolkit::camelize is a copy of sfFormDoctrine::camelize
   * @param $text Text to camelize
   * @return string A camelized form of $text
   * @see sfFormObject::camelize
   */
  public static function camelize($text)
  {
    return sfToolkit::pregtr($text, array(
      '#/(.?)#e' => "'::'.strtoupper('\\1')",
      '/(^|_|-)+(.)/e' => "strtoupper('\\2')" //call strtoupper on any character that comes after a ^, |, _, or -
    ));
  }

  /**
   * wfToolkit::underscore performs the inverse of wfToolkit::camelize. Unlike camelize, it does not undo namespaces
   * @param $text Text to underscore
   * @return string An underscored form of $text
   * @see wfToolkit::camelize
   */
  public static function underscore($text)
  {
    return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $text));
  }

  /**
   * Returns the array that results from calling $method on each item of $array
   * Emulates the Prototype.js function invoke
   * @param $array
   * @param $method
   * @return array
   */
  public static function arrayInvoke($array, $method)
  {
    $result = array();
    foreach ($array as $item)
    {
      $result[] = $item->$method();
    }
    return $result;
  }

  /**
   * Currently, this is a call through to PHP's uniqid function, but this may change in the future.
   * @param integer $length The $length of the id to return. Cannot be greater than 23.
   * @return A unique id
   */
  public static function uniqueId($length = 13)
  {
    if ($length > 23 || $length < 5)
    {
      throw new OutOfRangeException(sprintf('Length must be between 5 and 23, received "%i"', $length));
    }
    if ($length > 13)
    {
      return substr(uniqid('', true), 0, $length);
    }
    else
    {
      return substr(uniqid(), 0, $length);
    }
  }

  /**
   *
   * @param string|array $seed A seed for the hash. If seed is an array, we will hash a serialized form of the array
   * @param integer $length The length of the hash to return. Cannot be greater than 32.
   * @return A hash of length $length of the seed $seed. Currently uses PHP's md5 function
   */
  public static function hash($seed, $length = 13)
  {
    if (is_array($seed))
    {
      $values = array_map($seed, 'strval');
    }
    if ($length > 32 || $length < 5)
    {
      throw new OutOfRangeException(sprintf('Length must be between 5 and 32, received "%i"', $length));
    }
    return substr(md5($seed), 0, $length);
  }

  /**
   * Uses mcrypt to encrypt data
   * @param string $data
   * @return string the encrypted form of $data in base64
   * @see http://us3.php.net/manual/en/function.mcrypt-module-open.php
   * @see http://mcrypt.hellug.gr/lib/mcrypt.3.html
   * @see http://bugs.php.net/bug.php?id=39078 (explains why urlencode is necessary, + in urls)
   * @see wfToolkit::decrypt
   */
  public static function encrypt($data)
  {
    $mcryptKeySeed = sfConfig::get('app_mcrypt_key_seed', sfConfig::get('sf_csrf_secret'));
    $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $initializationVector = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_URANDOM); // switch to MCRYPT_DEV_RANDOM for super high security and huge loss of speed
    $keySize = mcrypt_enc_get_key_size($td);
    $key = substr(md5($mcryptKeySeed), 0, $keySize);

    mcrypt_generic_init($td, $key, $initializationVector);
    $encrypted = mcrypt_generic($td, $data);

    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    return urlencode(base64_encode($encrypted));
  }

  /**
   * Uses mcrypt to decrypt data
   * @param string $encrypted A string returned by wfToolkit::encrypt
   * @return string the unencrypted form of $encrypted
   * @see http://us3.php.net/manual/en/function.mcrypt-module-open.php
   * @see http://mcrypt.hellug.gr/lib/mcrypt.3.html
   * @see http://bugs.php.net/bug.php?id=39078 (explains why urlencode is necessary, + in urls)
   * @see wfToolkit::encrypt
   */
  public static function decrypt($encrypted)
  {
    $mcryptKeySeed = sfConfig::get('app_mcrypt_key_seed', sfConfig::get('sf_csrf_secret'));
    $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $initializationVector = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_URANDOM); // switch to MCRYPT_DEV_RANDOM for super high security and huge loss of speed
    $keySize = mcrypt_enc_get_key_size($td);
    $key = substr(md5($mcryptKeySeed), 0, $keySize);

    mcrypt_generic_init($td, $key, $initializationVector);
    $data = mdecrypt_generic($td, base64_decode(rawurldecode($encrypted)));

    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    return rtrim($data, "\0");
  }

  /**
   * @param array &$array The array to be sorted
   * @param string $sortFunction A function that should be called on each level of $array. $sortFunction should be pass-by-reference
   */
  public static function recursiveSort(&$array, $sortFunction = 'sort')
  {
    $sortFunction($array);
    foreach ($array as &$item)
    {
      if (is_array($item))
      {
        self::recursiveSort($item, $sortFunction);
      }
    }
  }

  /**
   * Converts an object into an array
   * @param mixed $object
   * @return array
   */
  public static function objectToArray($object)
  {
    if (!is_object($object) && !is_array($object))
    {
      return $object;
    }
    if (is_object($object))
    {
      $object = get_object_vars($object);
    }
    return array_map(array('wfToolkit', 'objectToArray'), $object);
  }

  /**
   * @param array $array1
   * @param array $array2
   * @return boolean True if the arrays (and all nested arrays) are equal, false otherwise
   */
  public static function arrayDeepEqual($array1, $array2)
  {
    return serialize($array1) == serialize($array2);
  }

  /**
   * @param DateTime|integer|string|null $dateTime
   * @return DateTime A DateTime object with the date set to $dateTime
   */
  public static function makeDateTime($dateTime = null)
  {
    if ($dateTime instanceof DateTime)
    {
      return $dateTime;
    }
    if (is_integer($dateTime))
    {
      return new DateTime('@' . $dateTime);
    }
    return new DateTime($dateTime);
  }

  /**
   * Replace all line breaks (\r and \n) with spaces
   * @param string $string
   * @return string Cleaned string
   */
  public static function stripLineBreaks($string)
  {
    return str_replace(array("\r\n", "\n", "\r"), ' ', $string);
  }

  /**
   * Convert XML data to associative array. Returns false on error.
   * @param string $xml
   * @return array
   */
  public static function xmlToArray($xml)
  {
    $xmlObject = simplexml_load_string($xml);
    if(!$xmlObject)
    {
      return null;
    }

    static::xmlToArrayHelper($xmlObject, $data);
    return array($xmlObject->getName() => $data);
  }

  /**
   * Recursive function to transform SimpleXML object into array
   * http://theserverpages.com/php/manual/en/ref.simplexml.php#45998
   *
   * @param SimpleXmlElement $obj
   * @param array $subject_array
   */
  protected static function xmlToArrayHelper($obj, &$subject_array=array())
  {
    foreach ((array) $obj as $key => $var)
    {
      if (is_object($var) || is_array($var))
      {
        if (count((array) $var) == 0)
        {
          $subject_array[$key] = null;
        }
        else
        {
          static::xmlToArrayHelper($var, $subject_array[$key]);
        }
      }
      else
      {
        $subject_array[$key] = $var;
      }
    }
  }

  /**
   * Converts an array to a string
   *
   * @param array $array
   * @param boolean $santizePasswords Replace the value all fields that start with 'password' with ******
   * @return string
   */
  public static function arrayToString($array, $santizePasswords = true)
  {
    if (!$array)
    {
      return '';
    }

    array_walk_recursive($array, function(&$item, $key) use ($santizePasswords) {
      if($santizePasswords && substr($key,0,8) == 'password')
      {
        $item = '******';
      }
      if (is_object($item))
      {
        $item = 'OBJECT(' . get_class($item) . ')';
      }
    });

    return preg_replace(array('/\n/','/\s+/'), array('',' '), var_export($array, true));
  }
}
