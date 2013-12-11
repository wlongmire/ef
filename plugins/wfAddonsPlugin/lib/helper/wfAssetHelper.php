<?php
/**
 * Adds javascripts and stylesheets from the supplied form to the response object.
 *
 * @param sfForm $form
 */
function use_stylesheets_and_javascripts_for_form(sfForm $form)
{
  use_javascripts_for_form($form);
  use_stylesheets_for_form($form);
}

function include_open_graph_properties()
{
  $context = sfContext::getInstance();
  $i18n = sfConfig::get('sf_i18n') ? $context->getI18N() : null;
  foreach ($context->getResponse()->getOpenGraphProperties() as $name => $content)
  {
    echo tag('meta', array('property' => 'og:'.$name, 'content' => null === $i18n ? $content : $i18n->__($content)))."\n";
  }
}