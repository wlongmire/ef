  public function executeBatch(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    if (!$ids = $request->getParameter('ids'))
    {
      $this->getUser()->setFlash('error', 'You must at least select one item.');

      $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
    }

    if (!$action = $request->getParameter('batch_action'))
    {
      $this->getUser()->setFlash('error', 'You must select an action to execute on the selected items.');

      $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
    }

    if (!method_exists($this, $method = 'execute'.ucfirst($action)))
    {
      throw new InvalidArgumentException(sprintf('You must create a "%s" method for action "%s"', $method, $action));
    }

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($action)))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }
 
    $validator = new sfValidatorDoctrineChoice(array(
        'multiple' => true,  
        'model' => '<?php echo $this->getModelClass() ?>',
        'query' => $this->addOwnerRestrictionToQuery(Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')->createQuery())
    ));
    try
    {
      // validate ids
      $ids = $validator->clean($ids);

      // execute batch
      $this->$method($request);
    }
    catch (sfValidatorError $e)
    {
      $this->getUser()->setFlash('error', 'Some items no longer exist or you do not have permission to perform this action on them.');
    }

    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }

  protected function executeBatchDelete(sfWebRequest $request)
  {
    // TBB: use collection delete rather than a delete query. This ensures
    // that the object's delete() method is called, which provides
    // for checking userHasPrivileges()

    $ids = $request->getParameter('ids');

    $items = Doctrine_Query::create()
      ->from('<?php echo $this->getModelClass() ?>')
      ->whereIn('<?php echo $this->getPrimaryKeys(true) ?>', $ids)
      ->execute();
    $count = count($items);
    $error = false;
    try
    {
      $items->delete();
    } catch (Exception $e)
    {
      $error = true;
    }

    if (($count == count($ids)) && (!$error))
    {
      $this->getUser()->setFlash('success', 'The selected items have been deleted successfully.');
    }
    else
    {
      $this->getUser()->setFlash('error', 'An error occurred while deleting the selected items.');
    }

    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }
