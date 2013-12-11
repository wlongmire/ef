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

    $validator = new sfValidatorDoctrineChoice(array('multiple' => true,  'model' => '<?php echo $this->getModelClass() ?>'));
    try
    {
      // validate ids
      $ids = $validator->clean($ids);

      // execute batch
      $this->$method($request);
    }
    catch (sfValidatorError $e)
    {
      $this->getUser()->setFlash('error', 'A problem occurs when deleting the selected items as some items do not exist anymore.');
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
  
  public function executeUpdateTree(sfWebRequest $request)
  {
    $newparent = $request->getParameter('newparent');
    
    //manually validate newparent parameter
    
    //make list of all ids
    $ids = array();
    foreach ($newparent as $key => $val)
    {
      $ids[$key] = true;
      if (!empty($val))
        $ids[$val] = true;
    }
    $ids = array_keys($ids);
    
    //validate if all id's exist
    $validator = new sfValidatorDoctrineChoice(array('model' => '<?php echo $this->getModelClass() ?>', 'multiple' => true));
    try
    {
      // validate ids
      $ids = $validator->clean($ids);

      // the id's validate, now update the tree
      $count = 0;
      $flash = "";

      foreach ($newparent as $id => $parentId)
      {
        if (!empty($parentId))
        {
          $node = Doctrine::getTable('<?php echo $this->getModelClass() ?>')->find($id);
          $parent = Doctrine::getTable('<?php echo $this->getModelClass() ?>')->find($parentId);
          
          if (!$parent->getNode()->isDescendantOfOrEqualTo($node))
          {
            $node->getNode()->moveAsFirstChildOf($parent);
            $node->save();

            $count++;

            $flash .= "<br/>Moved '".$node['name']."' under '".$parent['name']."'.";
          }
        }
      }

      if ($count > 0)
      {
        $this->getUser()->setFlash('success', sprintf("Tree order updated, moved %s item%s:".$flash, $count, ($count > 1 ? 's' : '')));
      }
      else
      {
        $this->getUser()->setFlash('error', "You must at least move one item to update the tree order");
      }
    }
    catch (sfValidatorError $e)
    {
      $this->getUser()->setFlash('error', 'Cannot update the tree order, maybe some item are deleted, try again');
    }
    
    $this->redirect('@<?php echo $this->getUrlForAction('list') ?>');
  }
