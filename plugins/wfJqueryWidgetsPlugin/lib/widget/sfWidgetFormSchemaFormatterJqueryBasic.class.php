<?php
class sfWidgetFormSchemaFormatterJqueryBasic extends sfWidgetFormSchemaFormatterJquery //class name has to start with sf because Symfony has it hard code in sfWidgetSchema
{
  protected
    $rowFormat       = "%error%\n%label%\n%field%%help%%hidden_fields%\n",
    $helpFormat      = '<p class="help">%help%</p>',
    $decoratorFormat = "<div %class%>%content%</div>",
    $errorListFormatInARow     = '%errors%',
    $errorRowFormatInARow      = "<span class='error'>%error%</span><br/>\n",
    $namedErrorRowFormatInARow = "<span class='error'>%name%: %error%</span><br/>\n";
}