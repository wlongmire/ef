<?php
class sfWidgetFormSchemaFormatterJqueryList extends sfWidgetFormSchemaFormatterJquery //class name has to start with sf because Symfony has it hard code in sfWidgetSchema
{
  protected
    $rowFormat       = "<li class=\"form-row\">%label%%error%%field%%help%%hidden_fields%</li>\n",
    $helpFormat      = '<p class="help">%help%</p>',
    $decoratorFormat = "<ul %class%>\n  %content%</ul>",
    $errorListFormatInARow     = '<ul class="error-list">%errors%</ul>',
    $errorRowFormat  = "%errors%";
}