<?php
class wfValidatorFileImageable extends sfValidatorFile
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('model');

    parent::configure($options, $messages);

    $this->setOption('path', Doctrine::getTable($options['model'])->getTemplate('Imageable')->getDirectoryPath(true));
    $this->addOption('validated_file_class', 'wfValidatedFileImageable');
    $this->addOption('mime_types', 'web_images');
  }
}

class wfValidatedFileImageable extends sfValidatedFile
{
    /**
   * Constructor.
   *
   * @param string $originalName  The original file name
   * @param string $type          The file content type
   * @param string $tempName      The absolute temporary path to the file
   * @param int    $size          The file size (in bytes)
   */
  public function __construct($originalName, $type, $tempName, $size, $path = null)
  {
    parent::__construct($originalName, $type, $tempName, $size, $path);
    if (!$this->path)
    {
      throw new InvalidArgumentException('sfValidatedFileImageable requires a path');
    }
  }

  public function getImageDirectory($size, $create = true, $dirMode = 0777)
  {
    $directory = $this->path . DIRECTORY_SEPARATOR . $size;

    if (!is_readable($directory))
    {
      if ($create && !@mkdir($directory, $dirMode, true))
      {
        // failed to create the directory
        throw new Exception(sprintf('Failed to create file upload directory "%s".', $directory));
      }

      // chmod the directory since it doesn't seem to work on recursive paths
      chmod($directory, $dirMode);
    }

    return $directory;
  }

  public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
  {
    $originalPath = $this->path;
    $this->path = $this->getImageDirectory('original', $create, $dirMode); //temporarily alter the path so the original is saved in the original folder
    $basename = parent::save($file, $fileMode, $create, $dirMode);
    $this->path = $originalPath;

    list($width, $height) = getimagesize($this->savedName);
    $croppedPath = $this->path . DIRECTORY_SEPARATOR . 'cropped_' . $basename;
    $smallestSide = min($width, $height);
    pkImageConverter::cropOriginal($this->savedName, $croppedPath, $smallestSide, $smallestSide);

    $sizes = sfConfig::get('app_image_sizes', array());
    foreach($sizes as $size)
    {
      // scale the full-size image
      $outPath = $this->getImageDirectory($size, $create, $dirMode) . DIRECTORY_SEPARATOR . $basename;
      pkImageConverter::scaleToFit($croppedPath, $outPath,
        sfConfig::get('app_image_' . $size . '_width'), sfConfig::get('app_image_' . $size . '_height'));
    }

    unlink($croppedPath); //we don't need to keep this around

    return $basename;
  }
}