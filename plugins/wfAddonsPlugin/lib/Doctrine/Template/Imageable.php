<?php

/**
 * Doctrine_Template_Imageable
 *
 * Adds the ability to thumbnail images
 */
class Doctrine_Template_Imageable extends Doctrine_Template
{
  /**
   * Array of Timestampable options
   *
   * @var string
   */
  protected $_options = array('image' =>  array('name'          => 'image',
                                                'alias'         => null,
                                                'type'          => 'string',
                                                'length'        => 50,
                                                'options'       => array()
                                               ),
                              'directory' => false
                            );

  /**
   * __construct
   *
   * @param string $array
   * @return void
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
    if (!$this->_options['directory'])
    {
      throw new Exception('"Directory" must be provided to any model implementing Imageable');
    }
    if ($this->_options['directory'][0] == '/')
    {
      throw new Exception('directory must be relative to the image folder');
    }
  }

  /**
   * Set table definition for Timestampable behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
      $name = $this->_options['image']['name'];
      if ($this->_options['image']['alias']) {
          $name .= ' as ' . $this->_options['image']['alias'];
      }
      $this->hasColumn($name, $this->_options['image']['type'], $this->_options['image']['length'], $this->_options['image']['options']);

      $this->addListener(new Doctrine_Template_Listener_Imageable());
  }

  public function getDirectoryPath($absolute = false)
  {
    $directory = $this->getOption('directory');
    if ($absolute)
    {
      $directory = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $directory;
    }
    return $directory;
  }

  public function getImageFieldName()
  {
    return $this->_options['image']['name'];
  }

  public function getImagePath($size, $absolute = true)
  {
    return $this->getDirectoryPath($absolute) . DIRECTORY_SEPARATOR . $size . DIRECTORY_SEPARATOR . $this->getInvoker()->image;
  }

  public function removeImages()
  {
    $invoker = $this->getInvoker();
    $sizes = sfConfig::get('app_image_types', array());

    if ($invoker->image)
    {
      foreach($sizes as $size)
      {
        $path = $invoker->getImagePath($size);
        if (file_exists($path))
        {
          unlink($path);
        }
      }
      $invoker['image'] = null;
    }
  }
}
