<?php
require_once dirname(__FILE__).'/../lib/eventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/eventAdminGeneratorHelper.class.php';

/**
 * event admin actions.
 *
 * @package    eventsfilter
 * @subpackage event
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eventAdminActions extends sfActions
{
  public function preExecute()
  {
    $this->configuration = new eventAdminGeneratorConfiguration();

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($this->getActionName())))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $this->dispatcher->notify(new sfEvent($this, 'admin.pre_execute', array('configuration' => $this->configuration)));

    $this->helper = new eventAdminGeneratorHelper();

    aTools::setAllowSlotEditing(false);
  }
  

  public function executeCopy()
  {
    $originalEvent = $this->getRoute()->getObject();
    $this->forward404If(!$this->canUserManage($originalEvent), 'User not allowed to manage this item');
    $this->event = $originalEvent->copy(false); //deep copy means copy all of the relations (i.e. MAKE COPIES of each relation, not what we want here)
    foreach($this->event->getTable()->getRelations() as $name => $relation) 
    {
      if ($relation->getType() != Doctrine_Relation::ONE || $originalEvent->relatedExists($name))
      {
        $this->event->$name = $originalEvent->$name;                
      }
    }
    $this->form = $this->configuration->getForm($this->event);
    $this->setTemplate('new');
  }
  
  protected function addSiteRestrictionToQuery(Doctrine_Query $query)
  {
    $tagNames = Site::current()->getFixedTagNames();
    if ($tagNames)
    {
      $tags = TagTable::findByNames($tagNames);
      if (count($tags))
      {
        EventTable::getInstance()->addTagFilterToQuery($query, $tags);
      }
      else
      {
        throw new Exception('All tags are missing');
      }
    }
    if (!$this->getUser()->hasCredential('admin') && !$this->getUser()->hasCredential('site_admin') && !$this->getUser()->hasCredential('venue_admin'))
    {
      $alias = $query->getRootAlias();
      $userId = $this->getUser()->getGuardUser()->getId();
      $query
        ->leftJoin($alias . '.EventOwner ro WITH ro.user_id = ?', $userId)
        ->leftJoin('vn.VenueOwner vo WITH vo.user_id = ?', $userId)
        ->addWhere('vo.user_id IS NOT NULL OR ro.user_id IS NOT NULL');
    }
    return $query;
  }  

  public function executeIndex(sfWebRequest $request)
  {
    // sorting
    if ($request->getParameter('sort'))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }

    // pager
    if ($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();
    $this->hasEvent = $this->getUser()->hasCredential(array('admin', 'site_admin', 'venue_admin'), false) || $this->pager->getNbResults();
    
    aTools::setAllowSlotEditing(false);

    // There is no really great way to determine whether the filters differ from the defaults
    // do it the tedious way
    $this->filtersActive = false;

    // Without this check we crash admin gen that has no filters
    if ($this->configuration->hasFilterForm())
    {
      $defaults = $this->configuration->getFilterDefaults();
      $filters = $this->getFilters();
    
      foreach ($filters as $key => $val)
      {
        if (isset($defaults[$key]))
        {
          if ($defaults[$key] == $val)
          {
            continue;
          }
          $this->filtersActive = true;
        }
        else
        {
          if (!$this->isEmptyFilter($val))
          {
            $this->filtersActive = true;
          }
        }
      }
    }
  }
  
  protected function isEmptyFilter($val)
  {
    if (!$val)
    {
      return true;
    }
    if (is_array($val))
    {
      foreach ($val as $v)
      {
        if ($v)
        {
          return false;
        }
      }
      return true;
    }
  }

  public function executeFilter(sfWebRequest $request)
  {
    $this->setPage(1);

    if ($request->hasParameter('_reset'))
    {
      $this->setFilters($this->configuration->getFilterDefaults());

      $this->redirect('@event_admin');
    }

    $this->filters = $this->configuration->getFilterForm($this->getFilters());

    $this->filters->bind($request->getParameter($this->filters->getName()));
    if ($this->filters->isValid())
    {
      $this->setFilters($this->filters->getValues());

      $this->redirect('@event_admin');
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();

    $this->setTemplate('index');
  }

  protected function canUserManage(Event $r)
  {    
    if ($this->getUser()->hasCredential('admin'))
    {
      return true;
    }
    if ($this->getUser()->hasCredential('site_admin', 'venue_manage'))
    {
      $fixedTagNames = Site::current()->getFixedTagNames();
      foreach($r->getTags() as $tagName)
      {
        if (in_array($tagName, $fixedTagNames))
        {
          return true;
        }
      }
    }
    $userId = $this->getUser()->getGuardUser()->getId();
    foreach ($r->EventOwner as $ro)
    {
      if ($ro->user_id == $userId)
      {
        return true;
      }
    }
    return $r->relatedExists('Venue') ? $this->canUserManageVenue($r->Venue) : false;
  }
  
  protected function canUserManageVenue(Venue $vn)
  {
    $userId = $this->getUser()->getGuardUser()->getId();
    foreach($vn->VenueOwner as $vo)
    {
      if ($vo->user_id == $userId)
      {
        return true;
      }
    }    
  }
  
  public function executeNew(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this->event = $this->form->getObject();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = $this->configuration->getForm();
    $this->event = $this->form->getObject();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->event = $this->getRoute()->getObject();
    $this->forward404If(!$this->canUserManage($this->event), 'User not allowed to manage this item');
    $this->form = $this->configuration->getForm($this->event);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->event = $this->getRoute()->getObject();
    $this->forward404If(!$this->canUserManage($this->event), 'User not allowed to manage this item');    
    $this->form = $this->configuration->getForm($this->event);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->event = $this->getRoute()->getObject();
    $this->forward404If(!$this->canUserManage($this->event), 'User not allowed to manage this item');
    
    $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->event)));

    $this->event->delete();

    $this->getUser()->setFlash('success', 'The item was deleted successfully.');

    $this->redirect('@event_admin');
  }

  public function executeBatch(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    if (!$ids = $request->getParameter('ids'))
    {
      $this->getUser()->setFlash('error', 'You must at least select one item.');

      $this->redirect('@event_admin');
    }

    if (!$action = $request->getParameter('batch_action'))
    {
      $this->getUser()->setFlash('error', 'You must select an action to execute on the selected items.');

      $this->redirect('@event_admin');
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
        'model' => 'Event',
        'query' => $this->addSiteRestrictionToQuery(Doctrine_Core::getTable('Event')->createQuery())
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

    $this->redirect('@event_admin');
  }

  protected function executeBatchDelete(sfWebRequest $request)
  {
    // TBB: use collection delete rather than a delete query. This ensures
    // that the object's delete() method is called, which provides
    // for checking userHasPrivileges()

    $ids = $request->getParameter('ids');

    $items = Doctrine_Query::create()
      ->from('Event')
      ->whereIn('id', $ids)
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

    $this->redirect('@event_admin');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $newFlash = __('Event created successfully.') . (Site::current()->hasCustomPaymentInstructions() ? 
                     ' ' . __('Payment is still required. Follow payment instructions below.') : '');
      $this->getUser()->setFlash('success', $form->getObject()->isNew() ? $newFlash : $this->__('The item was updated successfully.', null, 'apostrophe'));

      $event = $form->save();

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $event)));

      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('success', $this->getUser()->getFlash('success').' ' . $this->__('You can add another one below.', null, 'apostrophe'));

        $this->redirect('@event_admin_new');
      }
      elseif ($request->hasParameter('_save'))
      {
        $this->redirect('@event_admin_edit?id='.$event->getId());
      }
      // The default is _save_and_list
      else
      {
        $this->getUser()->setFlash('success', $this->getUser()->getFlash('success'));

        $this->redirect('@event_admin');
      }
    }
    else
    {
      $this->getUser()->setFlash('error', $this->__('The item has not been saved due to some errors.', null, 'apostrophe'));
    }
  }

  protected function __($s, $params, $catalogue)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
    return __($s, $params, $catalogue);
  }

  protected function getFilters()
  {
    return $this->getUser()->getAttribute('eventAdmin.filters', $this->configuration->getFilterDefaults(), 'admin_module');
  }

  protected function setFilters(array $filters)
  {
    return $this->getUser()->setAttribute('eventAdmin.filters', $filters, 'admin_module');
  }

  protected function getPager()
  {
    $pager = $this->configuration->getPager('Event');
    $pager->setQuery($this->buildQuery());
    $pager->setPage($this->getPage());
    $pager->init();

    return $pager;
  }

  protected function setPage($page)
  {
    $this->getUser()->setAttribute('eventAdmin.page', $page, 'admin_module');
  }

  protected function getPage()
  {
    return $this->getUser()->getAttribute('eventAdmin.page', 1, 'admin_module');
  }

  protected function buildQuery()
  {
    if (is_null($this->filters))
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $this->filters->setTableMethod('buildQueryForAdmin');

    $query = $this->filters->buildQuery($this->getFilters());

    $this->addSiteRestrictionToQuery($query);
    
    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
    $query = $event->getReturnValue();

    return $query;
  }

  protected function addSortQuery($query)
  {
    if (array(null, null) == ($sort = $this->getSort()))
    {
      return;
    }
    
    if (!in_array(strtolower($sort[1]), array('asc', 'desc')))
    {
      $sort[1] = 'asc';
    }

    $query->addOrderBy($sort[0] . ' ' . $sort[1]);
  }

  protected function getSort()
  {
    if (!is_null($sort = $this->getUser()->getAttribute('eventAdmin.sort', null, 'admin_module')))
    {
      return $sort;
    }

    $this->setSort($this->configuration->getDefaultSort());

    return $this->getUser()->getAttribute('eventAdmin.sort', null, 'admin_module');
  }

  protected function setSort(array $sort)
  {
    if (!is_null($sort[0]) && is_null($sort[1]))
    {
      $sort[1] = 'asc';
    }

    $this->getUser()->setAttribute('eventAdmin.sort', $sort, 'admin_module');
  }
}

