<?php header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex, nofollow" />
  <meta name="title" content="Error" />
  <meta name="description" content="Down for maintenance." />
  <title>Unavailable</title>
</head>
<body> 
  <div style="margin: 0 auto; width: 960px; text-align: center">
    <h1 style="padding: 20px; text-align: center; font-size: 2.2em;">Down for maintenance</h1>
    <h3>
      <?php if (isset($message) && $message): ?>
        <?php echo $message ?>
      <?php else: ?>
        We're down for maintenance. We'll be back soon.
      <?php endif ?>
    </h3>
  </div>
</body>
</html>