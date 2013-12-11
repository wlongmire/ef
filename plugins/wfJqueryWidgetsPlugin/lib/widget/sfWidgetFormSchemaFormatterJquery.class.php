<?php
class sfWidgetFormSchemaFormatterJquery extends sfWidgetFormSchemaFormatter //class name has to start with sf because Symfony has it hard code in sfWidgetSchema
{
  protected
      $errorListFormatInARow     = "  <ul class=\"error-list\">\n%errors%  </ul>\n",
      $decoratorClass = '';

  public function setDecoratorClass($class)
  {
    $this->decoratorClass = $class;
  }

  public function setDecoratorFormat($format)
  {
    $this->decoratorFormat = $format;
  }

  public function getDecoratorFormat()
  {
    if ($this->decoratorClass)
    {
      return strtr($this->decoratorFormat, array('%class%' => sprintf('class="%s"', $this->decoratorClass)));
    }
    else
    {
      return strtr($this->decoratorFormat, array('%class%' => ''));
    }
  }
}