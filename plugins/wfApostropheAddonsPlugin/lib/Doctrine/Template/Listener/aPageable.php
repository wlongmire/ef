<?php
/**
 * Listener for the aPageable behavior which automatically creates an aPage when the record is inserted
 */
class Doctrine_Template_Listener_aPageable extends Doctrine_Record_Listener
{
  /**
   * Create virtual page whenever a Playable is create
   * @param Doctrine_Event $event
   */
  public function postInsert(Doctrine_Event $event)
  {
    $invoker = $event->getInvoker();
    // Create a virtual page for this item
    $page = new aPage();
    $page->slug = $invoker->getVirtualPageSlug();
    $page->view_is_secure = false;
    $page->archived = false;

    if (method_exists($invoker, 'updateGeneratedPageValues'))
    {
      $invoker->updateGeneratedPageValues($page);
    }
    
    $page->save();

    if (method_exists($invoker, 'updateGeneratedPageContent'))
    {
      $invoker->updateGeneratedPageContent($page);
    }

    $invoker->Page = $page;

    $invoker->save();
  }
  
  /**
   *
   * @param Doctrine_Event $event
   */
  public function postUpdate(Doctrine_Event $event)
  {
    $invoker = $event->getInvoker();
    $virtualSlug = $invoker->getVirtualPageSlug();
    if ($invoker->Page->slug != $virtualSlug)
    {
      $invoker->Page->slug = $virtualSlug;
      $invoker->Page->save();
    }
  }

    /**
   * Delete associated page
   */
  public function postDelete(Doctrine_Event $event)
  {
    $event->getInvoker()->Page->delete();
  }
}