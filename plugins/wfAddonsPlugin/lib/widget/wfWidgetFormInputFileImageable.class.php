<?php
/**
 * Description of sfWidgetFormInputFileImageable
 *
 * * required : Whether the photo is required. If not required, a "delete photo" box will be provided if the photo exists
 * * record : The record the photo belonds to
 * * display_size : The size the current image should be displayed at
 * * max_size : The maximum size of the image (should be something in 'app_image_sizes'
 * * help : Help text to display below the input. False to disable.
 * @author jeremy
 */
class wfWidgetFormInputFileImageable extends sfWidgetFormInputFile
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addRequiredOption('record');

    $this->addOption('required', true);
    $this->addOption('display_size', 'full');
    $this->addOption('max_size', 'full');
    $this->addOption('help', 'Uploaded images (png/jpg/gif) will be resized to a maximum width of %width%px and maximum height of %height%px.<br/>
      For best results, upload a square image or edit your image with <a href="http://www.mypictr.com" target="_blank">Mypictr</a>.');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $render = array();

    if ($this->getOption('display_size'))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers('Imageable', 'Asset');
      $title = 'Upload a ' . strtolower(get_class($this->getOption('record'))) . ' image';
      $render['image'] = imageable_image($this->getOption('record'), $this->getOption('display_size'), array('alt_title' => $title));
    }

    $render['input'] = $this->renderTag('input',
        array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));

    if ($this->getOption('help'))
    {
      $render['help'] = sprintf('<p class="help">%s</p>',
                            strtr($this->getOption('help'), array(
                                '%width%' => sfConfig::get('app_image_' . $this->getOption('max_size') . '_width'),
                                '%height%' => sfConfig::get('app_image_' . $this->getOption('max_size') . '_height')
                            ))
                          );
    }

    if (!$this->getOption('required') && $this->getOption('record')->image)
    {
      $delete = new sfWidgetFormInputCheckbox();
      if (substr($name, -1) == ']')
      {
        $deleteName = substr($name, 0, -1) . '_delete' . ']';
      }
      else
      {
        $deleteName = $name . '_delete';
      }
      $render['delete'] = sprintf('<div class="imageDelete">%s %s</div>',
                            $delete->render($deleteName),
                            sprintf('<label for="%s">Delete Photo</label>', $delete->generateId($deleteName))
                          );
    }

    return sprintf('<div class="photoWidget">%s</div>', implode("\n", $render));
  }
}