<?php

$dir = dirname(__FILE__);
require_once $dir . '/../lib/autoload/wfCoreAutoload.class.php';
$autoload = wfCoreAutoload::getInstance();
//$autoload->setClassPath('sfFormField', $dir . '/../lib/form/sfFormField.class.php');
//$autoload->setClassPath('sfWidgetFormSchemaFormatter', $dir . '/../lib/widget/sfWidgetFormSchemaFormatter.class.php');
$autoload->setClassPath('wfProjectConfiguration', $dir . '/../lib/config/wfProjectConfiguration.class.php');
wfCoreAutoload::register();