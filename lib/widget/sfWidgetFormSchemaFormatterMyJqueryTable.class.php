<?php
class sfWidgetFormSchemaFormatterMyJqueryTable extends sfWidgetFormSchemaFormatterJqueryTable //class name has to start with sf because Symfony has it hard code in sfWidgetSchema
{
  protected
    $errorListFormatInARow     = '<tr class="error"><th></th><td><ul class="error_list">%errors%</ul></td></tr>';
}