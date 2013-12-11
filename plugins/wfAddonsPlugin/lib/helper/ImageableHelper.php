<?php

function _base_image_params($size)
{
  return array(
    'class' => $size,
    'width' => sfConfig::get('app_image_' . $size . '_width') . 'px',
    'height' => sfConfig::get('app_image_' . $size . '_height')  . 'px'
  );
}

function imageable_image_source($imageableModel, $size, $imageDirectory = null, $options = array())
{
  $options = array_merge(array(
    'absolute' => false
  ), $options);

  if (!$imageDirectory)
  {
    $imageDirectory = $imageableModel->getDirectoryPath();
  }

  $source = $imageDirectory . DIRECTORY_SEPARATOR . $size . DIRECTORY_SEPARATOR . $imageableModel['image'];
  if (!$imageableModel['image'] || !file_exists(sfConfig::get('sf_web_dir') . '/images/' . $source))
  {
    $source = $imageDirectory . DIRECTORY_SEPARATOR . 'default_' . $size . '.png';
  }

  if ($options['absolute'])
  {
    $source = sfContext::getInstance()->getRouting()->absolutizeUrl('/images/' . $source);
  }

  return $source;
}

function _get_imageable_title_text($model)
{
  foreach(array('username', 'name', 'title') as $field) //make some guesses as the field to use
  {
    if (isset($model[$field]) && $model[$field])
    {
      return 'photo of ' . $model[$field];
    }
  }
}

function imageable_image($model, $size = 'thumb', $imageParams = array())
{
  $directory = '';
  if (isset($imageParams['directory']))
  {
    $directory = $imageParams['directory'];
    unset($imageParams['directory']);
  }
  else if ($model instanceof Doctrine_Record)
  {
    $directory = $model->getDirectoryPath();
  }
  else
  {
    throw new Exception('Directory must be provided or $model must implement Imageable');
  }

  $params = array_merge(_base_image_params($size), $imageParams);

  if (!isset($params['alt_title']))
  {
    if (!isset($params['alt']))
    {
      $params['alt'] = _get_imageable_title_text($model);
    }
    if (!isset($params['title']))
    {
      $params['title'] = _get_imageable_title_text($model);
    }
  }

  return image_tag(imageable_image_source($model, $size, $directory), $params);
}