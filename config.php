<?php

require_once('config.inc.php');

define('DOMAIN', 'http://localhost:9090'); //domain
define('SITE_URL', DOMAIN.'/fomiz/');
define('GRAVATAR', SITE_URL.'ui/images/default_avatar.png'); //default user avatar
define('ADMIN_PATH', SITE_URL.'xadmin/');

/*****database config start ********/
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'mvc');
define('DB_PREFIX', 'fmz_');
define('DB_CHARSET', 'utf8');
/*****database config end   ********/


/* End of config.php  */
