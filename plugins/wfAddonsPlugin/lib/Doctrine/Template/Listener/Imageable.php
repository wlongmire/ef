<?php
/**
 * Listener for the Imageable behavior which automatically removes the images when the record is deleted
 */
class Doctrine_Template_Listener_Imageable extends Doctrine_Record_Listener
{
    /**
     * Remove associated images before the record is removed
     *
     * @param Doctrine_Event $event
     * @return void
     */
    public function preDelete(Doctrine_Event $event)
    {
      $event->getInvoker()->removeImages();
    }
}