<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head><title><?php echo $webtitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>ui/style.css" media="screen" title="style (screen)" />
<link rel="favicon" href="favicon.ico" type="image/x-icon" /> 
<link rel="alternate" type="application/atom+xml" title="<?php echo $options['webname']; ?> Atom 1.0" href="<?php echo site_url(); ?>/rss.php" />
<meta http-equiv="x-dns-prefetch-control" content="off"/>

</head>

<body>
<div id="main_menu">
  <ul>
    <?php echo $nav_menu;?>
  </ul>
</div>
<div id="header">
  <h1><a href="<?php echo site_url(); ?>" title="<?php echo $options['webname']; ?>"><?php echo $options['webname']; ?></a></h1>
  <h2><?php echo $options['description']; ?></h2>
</div>
