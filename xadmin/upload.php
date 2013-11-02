<?php
/*****************************************
 * @Author: Deloz
 * **************************************/

error_reporting(E_ALL);

session_start();

define('FOMIZ_ACCESS', TRUE);

require_once('../config.php');
require_once('check_login.php');
require_once('func.php');
header('Content-Type: text/html; charset=utf-8');
$db = DB::getInstance();
$opt = array();
foreach ( $db->result_all_table('options') as $o )
{
    $opt[$o->option_slug] = $o->option_value;
}

if ( $_GET['action'] === 'upload' )
{
    $file_types = array('.jpg', '.gif', '.png', 'jpeg');
    $err_msg = 'Error: ';
    if ( ! in_array(substr(strtolower($_FILES['fmzfile']['name']), -4, 4), $file_types)
        OR ( FALSE === strpos($_FILES['fmzfile']['type'], 'image') )
        OR $_FILES['fmzfile']['size'] > MAX_FILE_SIZE*1024
        )
    {
        upload_error_msg('file type is not allowed.. try again...');
    }
    $file_name  = date('Ymd-').time();
    $target_file = DIR_IMAGE.$file_name;

    if ( ! is_dir(DIR_IMAGE) )
    {
        $err_msg .= DIR_IMAGE.' is not a directory...';
        upload_error_msg($err_msg);
    }
    if ( ! is_writable(DIR_IMAGE) )
    {
        $err_msg .= DIR_IMAGE.' is not writeable...<br />';
        upload_error_msg($err_msg);
    }

    if (    is_uploaded_file($_FILES['fmzfile']['tmp_name'])
        AND move_uploaded_file($_FILES['fmzfile']['tmp_name'], $target_file)
        AND isset($_GET['types'])
        AND $_GET['types'] === 'single'
       )
    {
        if (    $opt['watermark'] 
            AND ( substr(strtolower($_FILES['fmzfile']['name']), -4, 4) !== '.gif') )
        {
            img_watermark($target_file);
        }
        die('<script>alert("upload success");history.back();parent.document.getElementById("pic").value="'.$file_name.'"</script>');
    }
    else
    {
        $err_msg .= 'upload failed....';
        upload_error_msg($err_msg);
    }

}


/* end ../editor/upload.php */
