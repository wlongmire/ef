  public function executeCreate(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();
    $this->addUserAsOwner($this-><?php echo $this->getSingularName() ?>);

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }
