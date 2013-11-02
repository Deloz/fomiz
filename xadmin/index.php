<?php
/*****************************************
 * @Author: Deloz
 * **************************************/

error_reporting(E_ALL);

session_start();
$start_time = microtime(TRUE);

define('FOMIZ_ACCESS', TRUE);

require_once('../config.php');
require_once('check_login.php');
require_once('func.php');

$db = DB::getInstance();
$user_name = $_SESSION['fomiz_name'];
$user_id = $_SESSION['fomiz_id'];

if ( isset($_GET['logout']) and ( strtolower($_GET['logout']) == 'do' ) )
{
    session_destroy();
    redirect('login.php');
}
require_once('admin_header.php');
if ( empty($_SERVER['QUERY_STRING']) )
{
    get_svr_info();
}
elseif ( isset($_GET['id']) and file_exists('mod/'.$_GET['id'].'.php') )
{
    require_once('mod/'.$_GET['id'].'.php');
}
else
{
    echo '<div id="content"><div class="message-error">This action does not exist!</div></div><div>';
}

require_once('admin_footer.php');

/* End of file ../wp-admin/index.php  */