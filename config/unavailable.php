<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="title" content="Home" />
	<title>Server Error</title>
  <link rel="stylesheet" type="text/css" media="screen" href="/css/page/error.css" />
	<link rel="shortcut icon" href="/favicon.ico" />
</head>

<body class="a-home">
  <div id="a-wrapper">
    <div id="header">
      <a href="/" class="logo">
        <img src="/images/logos/header.144.90.png" alt="Events Filter"/>
      </a>
    </div>
    <h1>EventsFilter Temporarily Down</h1>
    <h3>      <?php if (isset($message) && $message): ?>
        <?php echo $message ?>
      <?php else: ?>
        We're making changes to the site. We'll be back soon.
      <?php endif ?></h3>
</body>
</html>
