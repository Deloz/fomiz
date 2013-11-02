<?php
if ( !defined('FOMIZ_ACCESS') )
{
    echo '<script>document.location.href="login.php";</script>';
    exit(0);
}elseif ( !isset($_SESSION['admin']) or  ( $_SESSION['admin'] != TRUE ) )
{
    echo '<script>document.location.href="login.php";</script>';
    exit(0);
}
elseif ( !isset($_SESSION['fomiz_login'])
        or !isset($_SESSION['fomiz_id']) 
        or !isset($_SESSION['fomiz_name'])
        or $_SESSION['fomiz_login'] != md5($_SESSION['fomiz_id'].$_SESSION['fomiz_name']) )
{
     echo '<script>document.location.href="login.php";</script>';
     exit(0);
}


/* End of ../admin/check_login.php */