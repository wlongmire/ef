  public function executeNew(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();
  }

  public function executeNewChild(sfWebRequest $request)
  {
    $this->executeNew($request);
    $this->form->setDefault('parent_id', $request->getParameter('id'));
    $this->setTemplate('new');
  }