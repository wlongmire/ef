  public function executeEdit(sfWebRequest $request)
  {
    $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
    $this->forward404If(!$this->canUserManage($this-><?php echo $this->getSingularName() ?>), 'User not allowed to manage this item');
    $this->form = $this->configuration->getForm($this-><?php echo $this->getSingularName() ?>);
  }
