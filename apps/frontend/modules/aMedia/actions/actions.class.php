<?php
/**
 * See lib/base in this plugin for the actual code. You can extend that
 * class in your own application level override of this file
 * @package    Apostrophe
 * @author     P'unk Avenue <apostrophe@punkave.com>
 */
class aMediaActions extends BaseaMediaActions
{
  public function executeSetMediaItem(sfWebRequest $request)
  {
    $object = Doctrine_Core::getTable($request['table'])->findOneById($request['id']);
    $this->forward404If(!$object, 'No object found to set media item.');
    $returnUrlKey = 'aMedia-return_url-' . $request['table'] . '-' . $request['id'];

    if ($request->hasParameter('aMediaUnset'))
    {
      // No image chosen, so unset media item
      $object->media_item_id = null;
      $object->save();
      $this->getUser()->setFlash('success', 'Image cleared');
    }
    elseif (!$request->getParameter('aMediaCancel'))
    {
      if (!$request->hasParameter('aMediaId'))
      {        
        $this->forward404If(!$request['return_url'], 'Return URL must be set before accessing media library');
        $this->getUser()->setAttribute($returnUrlKey, $request['return_url']);
        
        $options = $request['options'] ?: array();
        if ($request['variant'])
        {
          $options = array_merge(aTools::getSlotVariantOptions($request['slot_type'] ?: 'aImage', $request['variant']), $options);
        }
        // Forward to media manager to pick image
        $url = $this->generateUrl('a_media_select', array_merge($options, array(
            'engine-slug' => '/admin/media',
            'multiple' => false,
            'label' => $request['label'] ?: 'Choose an image',
            'options' => $options,
            'aMediaId' => $object->media_item_id,
            'type' => 'image',
            'table' => $request['table'],
            'id' => $request['id'],
            'after' => $this->generateUrl('a_media_item_set', array('id' => $request['id'], 'table' => $request['table']))
        )));

        $this->redirect($url);
      }

      // Image chosen, set media item id
      $object->media_item_id = $request->getParameter('aMediaId');
      $object->save();
      $this->getUser()->setFlash('success', 'Image saved');
    }

    $this->redirect($this->getUser()->getAttribute($returnUrlKey));
  }
}