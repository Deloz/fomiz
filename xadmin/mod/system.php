<?php

if ( !defined('FOMIZ_ACCESS') )
{
    echo '<script>document.location.href="login.php";</script>';
    exit(0);
}

if ( isset($_GET['sub_id']) )
{
    switch ( strtolower($_GET['sub_id']) )
    {
        case 'users':
            sys_users();
            break;
        case 'backup':
            backup();
            break;
        case 'del_backup':
            del_backup();
            break;
        case 'sitemap':
            sitemap();
            break;
        default:
            admin_error();
    }
}
else
{
    sys_main();
}

function sys_main()
{
    global $db;
     if (sys_do_save())
     {
         admin_success(); 
    }
    $options = $db->result_all_table('options');
    if ( $options === FALSE ) :
        admin_error();
    else:
    $data = array();
    foreach ( $options as $opt )
    {
        $data[$opt->option_slug] = $opt->option_value;
    }
    
    ?>

<div id="content">
 
<div id="system-content">
  <div class="admin-heading">Site setting</div>

  <form action="?id=system" method="post">
    <br />
    <label>Site name</label>
    <br />
    <input type="text" name="site_name" value="<?php echo $data['webname']; ?>" size="100" />
    <br />
    <label>Site description</label>
    <br />
    <input type="text" name="site_description" size="100" value="<?php echo $data['description']; ?>" />
    <br />
    <label>Site keywords</label>
    <br />
    <input type="text" name="site_keywords" size="100" value="<?php echo $data['keywords']; ?>" />
    <br />
    <label>Site notice</label>
    <br />
    <input type="text" name="site_notice" size="100" value="<?php echo $data['notice']; ?>" />
    <br />
    <label>Each page show posts number</label>
    <input type="text" name="pagesize" size="50" value="<?php echo $data['pagesize']; ?>" />
    <br />
    <label>Most View posts show number</label>
    <input type="text" name="mostviewnum" size="50" value="<?php echo $data['mostviewnum']; ?>" />
    <br />
    <label>Newest Posts show number</label>
    <input type="text" name="newpostnum" size="50" value="<?php echo $data['newpostnum']; ?>" />
    <br />
    <label>Newest comments show number</label>
    <input type="text" name="newcomnum" size="50" value="<?php echo $data['newcomnum']; ?>" />
    <br />
    <label>Want to watermark? (TRUE or FALSE) </label>
    <input type="text" name="watermark" size="50" value="<?php echo $data['watermark']; ?>" />
    <br />
    <br />
    <br />
    <input type="submit" name="edit_main_settings" class="submit" value="Save" />
  </form>
</div>
<div>
<? 
endif; 
} ///end main();

function show_users()
{
    global $db;
    $user = $db->result_all_table('users');
        ?>
<div id="content">   <script type="text/javascript">
    function confirmDelete(msg){var data=confirm(msg+" ?"); return data;}
    </script>
  <div id="section-bar"> <span class="btn"><a href="?id=system&sub_id=users&action=add" title="Register new user">Register new user</a></span> </div>
  <div style="clear:both"></div>
  <table class="admin-table">
    <thead class="admin-table-header">
      <tr>
        <td class="admin-table-field">Login</td>
        <td class="admin-table-field">Nick name</td>
        <td align="left">Email</td>
        <td align="left">URL</td>
        <td>Registed</td>
        <td>Roles</td>
        <td></td>
      </tr>
    </thead>
    <tbody class="admin-table-content">
      <?php if ( $user !== FALSE ) :
      foreach ( $user as $u ): ?>
      <tr class="admin-table-tr">
        <td class="admin-table-field"><?php echo $u->user_name; ?></td>
        <td class="admin-table-field"><?php echo $u->user_nickname; ?></td>
        <td class="admin-table-field"><?php echo $u->user_email; ?></td>
        <td class="admin-table-field"><?php echo $u->user_url; ?></td>
        <td class="admin-table-field"><?php echo date('Y-m-j H:i:s', $u->user_time); ?></td>
        <td class="admin-table-field"><?php echo $u->role; ?></td>
        <td class="admin-table-field" align="right"><span class="btn-edit"><a href="?id=system&sub_id=users&action=edit&user_id=<?php echo $u->user_id; ?>" title="edit">Edit</a></span> <span class="btn-delete"><a href="?id=system&sub_id=users&action=delete&user_id=<?php echo $u->user_id; ?>" title="delete" onclick="return confirmDelete('Delete')">Delete</a></span></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<?php   //end show_users();
}

function edit_users()
{

    ?>
<div id="content">
  <div class="admin-heading">Edit profile</div>
  <?php
    if (sys_do_save())
    {
        admin_success(); 
    }
       global $db;
    $user = $db->sql_first_row('SELECT * FROM '.$db->get_prefix('users').' WHERE user_id='.trim($_GET['user_id']));
   ?>
  <div style="float:left">
    <form action="" method="post"  >
      <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
      <br />
      <label>Login</label>
      <br />
      <input type="text" value = "<?php echo $user->user_name; ?>" name = "login" size = "100" style = "width:300px;" />
      <br />
      <label>Nickname</label>
      <br />
      <input type="text" value = "<?php echo $user->user_nickname; ?>" name = "nickname" size = "100" style = "width:300px;" />
      <br />
      <label>Email</label>
      <br />
      <input type="text" value = "<?php echo $user->user_email; ?>" name = "email" size = "100" style = "width:300px;" />
      <br />
      <label>Website</label>
      <br />
      <input type="text" value = "<?php echo $user->user_url; ?>" name = "url" size = "100" style = "width:300px;" />
      <br />
      <label>Role</label>
      <br />
      <select  name = "role" style = "width:200px;">
        <option value="admin" <?php if ( $user->role == 'admin') { echo 'selected="selected"';}?> >Administrator</option>
        <option value="user" <?php if ( $user->role == 'user') { echo 'selected="selected"';}?>>User</option>
      </select>
      <br />
      <br />
      <input type="submit" class="submit"  name="edit_profile" value="Save"class="wymupdate" />
    </form>
  </div>
  <div style="float:left; margin-left: 60px;">
    <form action="" method="post"  >
      <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
      <input type="hidden" name="real_old_password" value="<?php echo $user->user_pwd; ?>" />
      <br />
      <label>Old password</label>
      <br />
      <input type="password" name = "old_password" size = "100" style = "width:300px;" />
      <br />
      <label>New password</label>
      <br />
      <input type="password" name = "new_password" size = "100" style = "width:300px;" />
      <br />
      <br />
      <input type="submit" class="submit"  name="edit_profile_password" value="Save"class="wymupdate" />
    </form>
  </div>
  <div style="clear:both"></div>
</div>
<?php  // end edit_users ;
}

function add_users()
{
    ?>
<div id="content">
  <div class="admin-heading">New User Registration</div>
  <?php
  if (sys_do_save())
    {
        admin_success(); 
    }
  ?>
  <form action="" method="post"  >
    <br />
    <label>Login name</label>
    <br />
    <input type="text" name = "login" size = "100" style = "width:300px;" />
    &nbsp&nbsp&nbsp<br />
    <label>Password</label>
    <br />
    <input type="password" name = "password" size = "100" style = "width:300px;" />
    &nbsp;&nbsp;&nbsp;<br />
    <label>Nick name</label>
    <br />
    <input type="text" name="nickname" size="100" style="width:300px;" />
    <br />
    <label>Email</label>
    <br />
    <input type="text" name = "email" size = "100" style = "width:300px;" />
    <br />
    <label>Website</label>
    <br />
    <input type="text" name="url" size="100" style="width:300px" />
    <br />
    <label>Role</label>
    <br />
    <select  name = "role" style = "width:200px;">
      <option value="admin" >Administrator</option>
      <option value="user" >User</option>
    </select>
    <br />
    <br />
    <input type="submit" class="submit"  name="register" value="Register"class="wymupdate" />
  </form>
</div>
<?php  //end add_users();
}

function backup()
{
?>
    <div id="content">
    <?php
  if (sys_do_save())
    {
        admin_success(); 
    }
  ?>
  <div style="float:left">
    <form method="post" action="">
    <br />
    <label>Database Backup</label>
    <br />
    <label>each size...</label>
    <input type="text" name="per_size" value="1024" />&nbsp;KB
    <br />
    <br />
    <input type="submit" name="sql_backup" value="back_up" class="wymupdate" />
    </form>
  </div>
   <div style="float:left; margin-left: 60px;">     <script type="text/javascript">
    function confirmDelete(msg){var data=confirm(msg+" ?"); return data;}
    </script> <br />
    <label>Backup File</label>
    <table>
        <?php 
        $dir = 'backup';
        $files = array();
        $directory = opendir($dir);
        while ( $item = readdir($directory) ):
        
            if ( $item != '.' AND $item != '..' AND $item != '.svn' ):
            
         ?>
    <tr>
        <td><span class="btn-delete"><input type="text" value="<?php echo $item; ?>" size="100"  disabled="disabled" /><a href="index.php?id=system&sub_id=del_backup&file_name=<?php echo $item; ?>" title="Delete" onclick="return confirmDelete('Delete')">Delete</a></span></td> 
        </tr>
        <?php endif; endwhile; ?>
    </table>
      

  </div>
  <div style="clear:both"></div>
  
    </div>
    
<?php
}


function sys_users()
{
    global $db;
    $msg = '';
    if ( isset($_GET['action']) )
    {
        if ( ($_GET['action'] == 'edit')  AND isset($_GET['user_id']) AND is_numeric($_GET['user_id']) )
        {
            $user = $db->sql_first_row('SELECT * FROM '.$db->get_prefix('users').' WHERE user_id='.trim($_GET['user_id']));
            if ( $user === FALSE or is_null($user) )
            {
                $msg = 'This user does not exist!';
                admin_error($msg);
            }
            else
            {
                edit_users();
            }
        }
        if ( $_GET['action'] == 'add' )
        {
            add_users();
        }
        if ( $_GET['action'] == 'delete' )
        {
            del_users();
        }
    }
    else
    {
        show_users();
    }
}

function del_users()
{
    global $db;
    if ( isset($_GET['user_id']) )
    {
        if ( is_numeric($_GET['user_id']) )
        {
            $sql = 'DELETE FROM '.$db->get_prefix('users').' WHERE user_id='.$_GET['user_id'];
            $db->query($sql);
            redirect('?id=system&sub_id=users');          
        }
        else
        {
            admin_error();
        }
    }
}

function sys_do_save()
{
    global $db;
    if ( isset($_POST['edit_main_settings']) )
    {
        $_POST = data_filter($_POST);
        $webname = $_POST['site_name'];
        $description = $_POST['site_description'];
        $keywords = $_POST['site_keywords'];
        $notice = $_POST['site_notice'];
        $pagesize = $_POST['pagesize'];
        $mostviewnum = $_POST['mostviewnum'];
        $newpostnum = $_POST['newpostnum'];
        $newcomnum = $_POST['newcomnum'];
        $watermark = strtoupper($_POST['watermark']);
               
        $data = array(
                    'webname'       =>      $webname,
                    'description'   =>      $description,
                    'keywords'      =>      $keywords,
                    'notice'        =>      $notice,
                    'pagesize'      =>      $pagesize,
                    'mostviewnum'   =>      $mostviewnum,
                    'newpostnum'    =>      $newpostnum,
                    'newcomnum'     =>      $newcomnum,
                    'watermark'     =>      $watermark
                    );
        $data = data_filter($data);
        foreach ( $data as $key => $value )
        {
            if ( !empty($value) )
            {
                if ( !is_numeric($value) )
                {
                    $value = '\''.$value.'\'';
                }
                $sql = 'UPDATE '.$db->get_prefix('options').' SET option_value='.$value.' WHERE option_slug=\''.$key.'\'';
                $db->query($sql);
            }
            else
            {
                return FALSE;
            }
        }
        return TRUE;
    }
    
    if ( isset($_POST['edit_profile']) )
    {
        $user_id = $_POST['user_id'];
        $user_name = $_POST['login'];
        $user_nickname = $_POST['nickname'];
        $user_email = $_POST['email'];
        $user_url = $_POST['url'];
        $role = $_POST['role'];
        $data = array( 'user_id'        =>      $user_id,
                       'user_name'      =>      $user_name,
                       'user_nickname'  =>      $user_nickname,
                       'user_email'     =>      $user_email,
                       'user_url'       =>      $user_url,
                       'role'           =>      $role
                    );
        $data = data_filter($data);
        foreach ( $data as $key => $value )
        {
            if ( !empty($value) )
            {
                if ( !is_numeric($value) )
                {
                    $value = '\''.$value.'\'';
                }
                $sql = 'UPDATE '.$db->get_prefix('users').' SET '.$key.'='.$value.' WHERE user_id='.$user_id;
                $db->query($sql);
            }
            else
            {
                return FALSE;
            }
        }
        return TRUE;
    }
    
    if ( isset($_POST['edit_profile_password']) )
    {
        $real_old = data_filter($_POST['real_old_password']);
        $old_pwd = data_filter($_POST['old_password']);
        $new_pwd = data_filter($_POST['new_password']);
        $user_id = data_filter($_POST['user_id']);
        if ( !empty($old_pwd) and !empty($new_pwd) and encrypt_password($old_pwd) == $real_old )
        {
            $sql = 'UPDATE '.$db->get_prefix('users').' SET user_pwd=\''.encrypt_password($new_pwd).'\' WHERE user_id='.$user_id;
            $db->query($sql);
            admin_success();
        }
        else 
        {
            err_msg('Wrong old password');
        }
    }
    
    if ( isset($_POST['register']) )
    {
        $user_name = data_filter($_POST['login']);
        $user_pwd = data_filter($_POST['password']);
        $user_nickname = data_filter($_POST['nickname']);
        $user_email = data_filter($_POST['email']);
        $user_url = data_filter($_POST['url']);
        $role = data_filter($_POST['role']);
        
        $data = array( 'user_name'      =>      $user_name,
                       'user_pwd'       =>      encrypt_password($user_pwd),
                       'user_nickname'  =>      $user_nickname,
                       'user_email'     =>      $user_email,
                       'role'           =>      $role,
                       'user_url'       =>      $user_url,
                       'user_time'      =>      time()
                        );
        if ( empty($user_name) )
        {
            err_msg('login name is empty...');
        }
        elseif ( empty($user_pwd) )
        {
            err_msg('password is empty...');
        }
        elseif ( empty($user_nickname) )
        {
            err_msg('nick name is empty...');
        }
        else
        {
            $result = $db->insert('users', $data);
            if ( $result !== FALSE )
            {
                admin_success();
            }
            else
            {
                err_msg('reg failed..');
            }
        }
    }
 
    if ( isset($_POST['sql_backup']) )
    {
        $_POST = data_filter($_POST);
        $per_size = $_POST['per_size'];
        if ( $per_size === '' OR !is_numeric($per_size) )
        {
            $per_size = 1024;
        }
        $tables = $db->list_tables();
        if ( $tables !== FALSE AND $tables !== NULL )
        {
            $return = '';
            
            foreach ( $tables as $table )
            {
                $s = 'Tables_in_'.DB_NAME;
                $table_name = $table->$s;
                $return .= "DROP TABLE IF EXISTS `{$table_name}`;\r\n\r\n";
                $sql = 'SHOW CREATE TABLE `'.$table_name.'`';
                $result = $db->sql_rows($sql, TRUE);
                if ( $result !== NULL AND $result !== FALSE )
                {               
                    $s = 'Create Table';
                    $return .= $result[0][$s].";\r\n\r\n";
                }
                $result = $db->query('SELECT * FROM '.$table_name);
                $num_fields = $db->field_count();
                
               
                while ( $row = $db->result_first_row($result, TRUE) )
                {
                    $return .= 'INSERT INTO `'.$table_name.'` VALUES(';
                    $i = 0;
                    foreach ( $row as $key => $val )
                    {
                        $val = str_replace("\n", "\\n", addslashes($val));
                        if ( isset($val) )
                        {
                            $return .= '"'.$val.'"';
                        }
                        else
                        {
                            $return .= '"';
                        }
                        if ( $i < ( $num_fields - 1 ) )
                        {
                            $return .= ',';
                        } 
                        $i++;
                    }
                    $return .= ");\r\n";
                }
               
                $return .= "\r\n";
            }   
            $bk_file = 'backup/back-'.date('Ymd-', time()).time().'.sql.gz';
            $gzdata = gzencode($return, 9);
            $fp = fopen($bk_file, 'w+b');        
            fwrite($fp, $gzdata);
            fclose($fp); 
            admin_success('Backup Success !!<br /><a href="'.ADMIN_PATH.$bk_file.'">DownLoad</a><br />');
        }
        else
        {
            err_msg('Backup Failed...');
        }
    }
    
}

function del_backup()
{
    if ( isset($_GET['file_name']) )
    {
        $file_name = 'backup/'.$_GET['file_name'];
        if ( file_exists($file_name) )
        {
            unlink($file_name);
        }
    }
    redirect('?id=system&sub_id=backup');
}


?>
