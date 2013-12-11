<?php

/**
 * profile actions.
 *
 * @package    eventsfilter
 * @subpackage profile
 * @author     Jeremy Kauffman
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profileActions extends myFilterActions
{
  protected $filterTypes = array(
      'discipline' => 'table', 
      'location' => 'table',
      'category' => 'table',
      'tag' => 'multi-table',
      'profile' => 'table',
      'name' => 'string',
  ),
    $exclusiveFilters = array('profile'),
    $requiredFilters = array(),    
    $model = 'profile';
  
  protected function getDefaultFilters()
  {
    return array('name' => '');
  }

  /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(wfWebRequest $request)
  {
    $this->filters = $this->buildFilters();
    $this->applied_filters = array_diff_assoc($this->getFilterAttributeValues(), $this->getDefaultFilters());
    
    $this->pager = new sfDoctrinePager('Profile', 50);

    $query = ProfileTable::getInstance()->buildQueryForFilters($this->filters);

    $idQuery = clone $query;
    $this->profileIds = $idQuery->select('id')->removeQueryPart('orderby')->setHydrationMode(Doctrine_Core::HYDRATE_SINGLE_SCALAR)->execute();

    $this->pager->setQuery($query);
    $this->pager->setPage($this->getUser()->getFlash('page'));
    $this->pager->init();

    $this->profiles = $this->pager->getResults();
    if ($request->isXmlHttpRequest() || $request['callback'])
    {
      $html = $this->getPartial('list', array(
          'filters' => $this->filters,
          'pager' => $this->pager,
          'profiles' => $this->profiles,
          'applied_filters' => $this->applied_filters
      )); 
      if ($request['callback'])
      {
        if ($request['format'] == 'json')
        {
          return $this->renderJSONP($this->profiles);
        }
        return $this->renderJSONP(array('html' => $html));
      }
      return $this->renderText($html);
    }      
    
    $this->setLayout('filter');
  }
  
  public function executeSearch(sfWebRequest $request)
  {
    $this->setFilter('name', null);
    $this->executeIndex($request);
    $this->form = new ProfileSearchFilter();
    $this->setLayout('layout');
  }
  
  public function executePaginate(sfWebRequest $request) 
  {
    $this->getUser()->setFlash('page', $request['page'], !$request->isXmlHttpRequest()); //this approach is progressive
    $this->forwardIf($request->isXmlHttpRequest(), 'profile', 'index');
    $this->redirect($this->generateUrl('profile_index'));    
  }
  
  protected function doPostFilter(sfWebRequest $request)
  {
    $this->forwardIf($request->isXmlHttpRequest(), 'profile', 'index');
    $this->redirect($this->generateUrl('profile_index'));
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->profile = $this->getRoute()->getObject();
    if ($request['callback'])
    {
      return $this->renderJSONP(array(
          'html' => $this->getComponent('profile', 'details', array(
            'profile' => $this->profile
          ))
      ));
    }
    if (!$request->isXmlHttpRequest())
    {
      $this->getResponse()->setSlot('filter-details', $this->getComponent('profile', 'details', array('profile' => $this->profile)));
      $this->getResponse()->addMeta('description', myTools::blurbToMeta($this->profile['blurb']));
      $this->forward($request['listings'] == 'event' ? 'event' : 'profile', 'index');
    }
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404If(!in_array($request['tab'], array('basic info', 'work', 'group members', 'sharing')));
    $gu = $this->getUser()->getGuardUser();
    if (!$gu->relatedExists('Profile'))
    {
      $this->isNew = true;
      $gu->Profile = new Profile();
      $gu->Profile->User = $gu; //why is this necessary?
      $gu->Profile->name = $gu->full_name;
    }
    else
    {
      $this->isNew = !$gu->Profile->Categories->count(); //consider it new if it's not categorized
    }
    $method = sprintf('doTab%s', ucfirst(wfToolkit::camelize(str_replace(' ', '-', $request['tab']))));
    $this->$method($request);
  }

  public function executeApiShow(sfWebRequest $request)
  {
    $this->profile = $this->getRoute()->getObject();
    $data = $this->profile->getApiData();

    return $this->renderJSONP($data);
  }
  
  public function executeApiList(sfWebRequest $request)
  {
    $filters = array_intersect_key($request->getGetParameters(), $this->filterTypes);
    $this->setFilters(array_merge(Site::current()->getDefaultFilters(), $filters));
    $this->filters = $this->buildFilters();

    $this->pager = new sfDoctrinePager('Profile', 50);

    $query = ProfileTable::getInstance()->buildQueryForFilters($this->filters);

    $this->pager->setQuery($query);
    $this->pager->setPage($this->getUser()->getFlash('page'));
    $this->pager->init();

    $this->profiles = $this->pager->getResults();
    return $this->renderJSONP($this->profiles);
  }

  protected function doTabBasicInfo(sfWebRequest $request)
  {
    $this->form = new ProfileFormBasicInfo($this->getUser()->getProfile(), array('is_admin' => $this->getUser()->hasCredential('admin')));
    $this->processEditForm($this->form, $request);
    $this->submitLabel = $this->isNew ? 'Save and Continue' : 'Update';
  }
  
  protected function doTabWork(sfWebRequest $request)
  {
    $profile = $this->getUser()->getProfile();
    if (!$profile->exists())
    {
      $this->getUser()->setFlash('error', 'Please complete basic info first.');
      $this->redirect($this->generateUrl('profile_edit', array('tab' => 'basic info')));
    }
    $this->form = new ProfileFormCategorize($profile);
    $this->processEditForm($this->form, $request);
    $this->submitLabel = $this->isNew ? 'Save and Continue' : 'Update';
  }
  
  protected function doTabSharing(sfWebRequest $request)
  {
    $this->setTemplate('sharing');
    $this->profile = $this->getUser()->getProfile();
  }
  
  protected function doTabGroupMembers(sfWebRequest $request)
  {
    $profile = $this->getUser()->getProfile();
    $this->forward404If(!$profile->is_group, 'Tab group members can only be accessed for group profiles');
    $this->form = new ProfileFormGroup($profile);
    $this->processEditForm($this->form, $request);
    $this->submitLabel = 'Update Group Members';
  }  
    
  protected function processEditForm(BaseForm $form, sfWebRequest $request)
  {
    if ($request->isMethod('post'))
    {
      if ($form->bindAndSave($request->getPostParameter($form->getName()), $request->getFiles($form->getName())))
      {
        $nextTab = $request['tab'];
        $flash = 'Profile updated.';
        if ($this->isNew && $request['tab'] == 'basic info')
        {
          $nextTab = 'work';
          $flash = 'Basic info updated. Next, tell us about your work.';
        }
        if ($this->isNew && $request['tab'] == 'work')
        {
          $nextTab = 'work';
          $flash = sprintf('Profile complete! Click <a href="%s">here</a> to return to listings.',
                    $this->generateUrl('homepage'));
        }
        $this->getUser()->setFlash('success', $flash);
        if ($this->isNew)
        {
          $this->getUser()->getGuardUser()->save(); //otherwise relation isn't updated
        }
        $this->redirect('profile_edit', array('tab' => $nextTab));
      }
    }
  }
  
  public function setFilter($filter, $value, $checkExclusive = true)
  {
    if ($filter == 'profile')
    {
      $this->setFilter('name', null);
    }
    return parent::setFilter($filter, $value, $checkExclusive);
  }
  
  protected function getIndexRoute()
  {
    return 'profile_index';
  }

  protected function findForFilters($filters)  {
    print_r($filters);
    $query = EventTable::getInstance()->buildQueryForFilters($filters);
    $profiles = $query->execute();
    return($profiles);
  }
}
