<?php

/**
 * Profile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    eventsfilter
 * @subpackage model
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Profile extends BaseProfile
{
  public function toParams()
  {
    return Profile::parameterize($this);
  }
  
  public function getApiData()
  {
    $data = array(
        'id' => $this->id,
        'name' => $this->name,
        'blurb' => $this->blurb
    );  
    
    foreach(array('disciplines' => "Disciplines") as $key => $rel)
    {

      foreach($this->$rel as $item) {
        $data[$key][] = $item->getApiData();
      }
      
      if (!isset($data[$key])){
        $data[$key] = null;
      }
    }

    if ($this->media_item_id)
    {
      $data += array(
        'original_picture_url' => $this->Picture->getOriginalPath(),
        'scaled_picture_url' => $this->Picture->simpleRender(array('width' => 200, 'maxHeight' => 200, 'height' => false, 'resizeType' => 's'))
      );
    }
    else
    {
      $data += array('original_picture_url' => null, 'scaled_picture_url' => null);
    }
    
    return $data;
  }

  public static function parameterize($profile)
  {
    return array('id' => $profile['id'], 'name' => myTools::urlify($profile['name']));
  }
  
  public function getClaimToken()
  {
    return urlencode(wfToolkit::encrypt($this->id));
  }
}