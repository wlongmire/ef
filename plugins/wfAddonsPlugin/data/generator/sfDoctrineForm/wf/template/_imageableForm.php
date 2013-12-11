 /**
  *Overridden removeFile to call removeImages if the object implements Imageable
  *@see parent::removeFile($field)
  */
  protected function removeFile($field)
  {
    if ($field == $this->object->getImageFieldName())
    {
      $this->object->removeImages();
    }

    return parent::removeFile($field);
  }