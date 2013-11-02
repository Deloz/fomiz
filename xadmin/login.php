<?php
error_reporting(E_ALL);
session_start();
$login_error = '';
require_once('../config.php');
if ( isset($_POST['login_submit']) )
{
    $db = DB::getInstance();
    $user = $db->sql_first_row('SELECT * FROM '.$db->get_prefix('users').' WHERE user_name=\''.trim($_POST['login']).'\'');
    if ( $user === FALSE or is_null($user) ) //SQL query error
    {
        $login_error = 'wrong <strong>uername</strong> or <strong>password</strong>';
    }
    elseif ( encrypt_password($_POST['password']) == $user->user_pwd )
    {
        if ( $user->role == 'admin' )
        {
            $_SESSION['admin'] = TRUE;
        }
        $_SESSION['fomiz_id'] = $user->user_id;
        $_SESSION['fomiz_name'] = $user->user_name;
        $_SESSION['fomiz_login'] = md5($user->user_id.$user->user_name);

        redirect('index.php');
    }
    else
    {
        $login_error = 'wrong <strong>uername</strong> or <strong>password</strong>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Fomiz::Admin</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="css/login.css" media="all" type="text/css" />
    </head>
    <body>
        <div id="container">
            <div id="logo">Fomiz System.</div>
            <div id="login_area">
            <form action="" method="post">
                <p><label for="login" >Username</label><input name="login" type="text" /></p>
                <p><label for="password" >Password</label><input name="password" type="password" /></p>       
                <p><input type="submit" name="login_submit" value="Login..." /></p><p><span class="error"><?php echo $login_error; ?></span></p>
            </form>
            </div>
        </div>
    </body>
</html>
