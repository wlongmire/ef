<?php
class sfWidgetFormSchemaFormatterJqueryTable extends sfWidgetFormSchemaFormatterJquery //class name has to start with sf because Symfony has it hard code in sfWidgetSchema
{
  protected
    $rowFormat       = "%error%<tr>\n  <th>%label%</th>\n  <td>%field%%help%%hidden_fields%</td>\n</tr>\n",
    $helpFormat      = '<p class="help">%help%</p>',
    $decoratorFormat = "<table %class%>\n  %content%</table>",
    $errorListFormatInARow     = '<tr class="error"><th></th><td><ul class="error-list">%errors%</ul></td></tr>',
    $errorRowFormat  = "<tr><th></th><td>\n%errors%</td></tr>\n";
}