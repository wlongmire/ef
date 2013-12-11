  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $object = $this->getRoute()->getObject();
    
    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $object)));

    if ($object->getNode()->isValidNode())
    {
      $object->getNode()->delete();
    }
    else
    {
      $object->delete();
    }

    $this->getUser()->setFlash('success', 'The item was deleted successfully.');

    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }