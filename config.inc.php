<?php

define('ROOT_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('EXT', '.php');
define('APP_PATH', ROOT_PATH.'app'.DIRECTORY_SEPARATOR);
define('VIEW_PATH',  APP_PATH.'view'.DIRECTORY_SEPARATOR);
define('MODEL_PATH', APP_PATH.'model'.DIRECTORY_SEPARATOR);
define('CACHE_PATH', APP_PATH.'cache'.DIRECTORY_SEPARATOR);
define('CTRL_PATH',  APP_PATH.'ctrl'.DIRECTORY_SEPARATOR);

define('COOKIE_PORTIONS' , '_fomiz_' ); 


/*****upload directory start *******/
define('IMAGE_DIRECTORY_NAME', 'images');
define('DIR_IMAGE',     ROOT_PATH.IMAGE_DIRECTORY_NAME.DIRECTORY_SEPARATOR);
define('DIR_THUMB_MID', ROOT_PATH.'thumbs'.DIRECTORY_SEPARATOR);
define('DIR_THUMB',     ROOT_PATH.'smallthumbs'.DIRECTORY_SEPARATOR);
define('DIR_TEMP',      ROOT_PATH.'temp'.DIRECTORY_SEPARATOR);
/*****upload directory end   *******/
define('MAX_FILE_SIZE', 2046);// file size: 2046 KB

date_default_timezone_set('Asia/Shanghai');


if(get_magic_quotes_gpc())
{
    function stripslashes_deep($value)
    {
        $value = is_array($value) ? array_map('stripslashes_deep', $value) : (isset($value) ? stripslashes($value) : null);
        return $value;
    }
 
    $_POST = stripslashes_deep($_POST);
    $_GET = stripslashes_deep($_GET);
    $_COOKIE = stripslashes_deep($_COOKIE);
}

$file_array = array('func', 'route', 'fomiz', 'db', 'model');
foreach ( $file_array as $file )
{
    $file = APP_PATH.$file.EXT;
    if ( file_exists($file) )
    {
        require_once($file);
    }
    else
    {
        die('file '.$file.' does not exist....please check...');
    }
}

/* End of file ./config.inc.php */
