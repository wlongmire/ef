<?php

/**
 * Doctrine_Template_Imageable
 *
 * Adds the ability to thumbnail images
 */
class Doctrine_Template_aPageable extends Doctrine_Template
{
  /**
   * Array of Timestampable options
   *
   * @var string
   */
  protected $_options = array('page' =>  array(
                                                'alias'         => null,
                                                'length'        => 8,
                                                'options'       => array(),
                                              ),
                              'onDelete' => 'RESTRICT',
                              'onUpdate' => 'CASCADE',
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
  }

  public function setUp()
  {
    $invoker = $this->getInvoker();
    if (!method_exists($invoker, 'getVirtualPageSlug'))
    {
      throw new LogicException(sprintf('Model "%s" implementing aPageable must have getVirtualPageSlug method', get_class($invoker)));
    }
  }

  /**
   * Set table definition for Timestampable behavior
   *
   * @return void
   */
  public function setTableDefinition()
  {
    $name = 'page_id';
    if ($this->_options['page']['alias']) {
        $name .= ' as ' . $this->_options['page']['alias'];
    }
    $this->hasColumn($name, 'integer', $this->_options['page']['length'], $this->_options['page']['options']);

    $this->hasOne('aPage as Page', array(
         'local' => 'page_id',
         'foreign' => 'id',
         'onDelete' => $this->_options['onDelete'],
         'onUpdate' => $this->_options['onUpdate']
    ));

    $this->addListener(new Doctrine_Template_Listener_aPageable());
  }

  /**
   * @return aMediaItem|null
   */
  public function getFirstMediaItem()
  {
    $object = $this->getInvoker();
    $media = $object->Page->findMedia('image', 1);
    return reset($media);
  }

  /**
   * @param Playable $playable
   */
  public function populatePage()
  {
    $object = $this->getInvoker();
    if($object->page_id)
    {
      $query = aPageTable::queryWithSlots();
      $query->where('p.id = ?', $object->page_id);
      $pages = $query->execute();
      aTools::cacheVirtualPages($pages);
      if (count($pages))
      {
        $object->Page = $pages->getFirst();
      }
    }
  }
}
