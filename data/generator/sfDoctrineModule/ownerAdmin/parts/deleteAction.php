  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
    $this->forward404If(!$this->canUserManage($this-><?php echo $this->getSingularName() ?>), 'User not allowed to manage this item');
    
    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this-><?php echo $this->getSingularName() ?>)));

    $this-><?php echo $this->getSingularName() ?>->delete();

    $this->getUser()->setFlash('success', 'The item was deleted successfully.');

    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }
