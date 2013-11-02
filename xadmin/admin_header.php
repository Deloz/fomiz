<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8" />
        <title>Fomiz system :: Admin</title>
        <link rel="stylesheet" href="css/style.css" media="all" type="text/css" />
    </head>
    <body>
    
    <div id="header-inside-buttons">
    Welcome, <a href="?id=system&sub_id=users&action=edit&user_id=<?php echo $_SESSION['fomiz_id']; ?>"><strong>admin</strong>&nbsp;</a>&nbsp;<a href="<?php echo site_url(); ?>" target="_blank">site</a>&nbsp;&nbsp;<a href="<?php echo ADMIN_PATH; ?>?logout=do">logout</a>
    </div>
    <div id="header">
        <div id="header-inside">
            <div id="logo">
            <img src="css/logo.png" />
            </div>
            <ul id="menu">
                <li><a href="./" >Index</a></li>
                <li><a href="?id=posts" >Content</a></li>
                <li><a href="?id=tags">Tags</a></li>
                <li><a href="?id=system">System</a></li>
            </ul>
            <ul id="sub-menu">
                <?php admin_menu(); ?>
            </ul>
        </div>
    </div>
     <div style="clear:both;"></div>

    <div>
            </div>
    