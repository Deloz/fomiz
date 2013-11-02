<?php
/*****************************************
 * @Author: Deloz
 * **************************************/

if ( !defined('FOMIZ_ACCESS') )
{
    echo '<script>document.location.href="login.php";</script>';
    exit(0);
}

if ( isset($_GET['action']) )
{
    switch ( $_GET['action'] )
    {
        case 'add_post':
            add_post();
            break;
        case 'edit_post':
            edit_post();
            break;
        case 'delete_post':
            del_post();
            break;
        default:
            admin_error();
    }
}
else
{
    show_posts();
}

function show_posts()
{
    global $db;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page_size = 10;
    $sql = 'SELECT * FROM '.$db->get_prefix('posts').' ORDER BY post_time DESC LIMIT '.($page-1)*$page_size.','.$page_size;
    $posts = $db->sql_rows($sql);
    $nums = $db->total('posts');
    ?>

<div id="content">
  <script type="text/javascript">
    function confirmDelete(msg){var data=confirm(msg+" ?"); return data;}
    </script>
  <div id="section-bar"> <span class="btn"><a href="?id=posts&action=add_post"  title="+ Add">+ Add</a></span> </div>
  <div style="clear:both"></div>
  <table class="admin-table">
    <thead class="admin-table-header">
      <tr>
        <td class="admin-table-field">Post title</td>
        <td class="admin-table-field">Date</td>
        <td class="admin-table-field">Views</td>
        <td></td>
      </tr>
    </thead>
    <tbody class="admin-table-content">
      <?php
     if ( FALSE !== $posts ) :
     foreach ( $posts as $post ) :
     ?>
      <tr style="background:#F2F2F2;" class="admin-table-tr">
        <td  class="admin-table-titles admin-table-field"><?php echo get_post_link(myhtml_entity_decode(stripslashes($post->post_title)), $post->post_id);?></td>
        <td class="admin-table-field date" align=" "><?php echo date('Y-m-j H:i:s', $post->post_time); ?></td>
        <td class="admin-table-field date" align=" "><?php echo $post->post_hit; ?></td>
        <td class="admin-table-field" align="right"><span class="btn-edit"><a href="index.php?id=posts&action=edit_post&post_id=<?php echo $post->post_id; ?>" title="Edit">Edit</a></span> <span class="btn-delete"><a href="index.php?id=posts&action=delete_post&post_id=<?php echo $post->post_id; ?>" title="Delete" onclick="return confirmDelete('Delete')">Delete</a></span></td>
      </tr>
      <?php
      endforeach;endif;
      ?>
    </tbody>
  </table>
   <br />
  <?php  echo admin_page($nums, $page, $page_size);?> <!-- page -->
</div>
<?php
}  //end show_posts();

function add_post()
{
    post_do_save();
    ?>
<div id="content">
  <div class="admin-heading">Create post</div>
  <form action="" method="post"  name="post_form">
    <input type="hidden" name="user" value="<?php echo $_SESSION['fomiz_name'];?>" />
    <br />
    <label>Title</label>
    <br />
    <input type="text" value="" name="post_title" size = "100" />
    <br />
    <label>Name (slug)</label>
    <br />
    <input type="text" value="" name="post_slug" size = "100" />
    <br />
          <label>Views</label>
          <br />
          <input type="text" value="0" name="post_hit" size="20" /></td>
    <br />
          <label>Tags</label>
          <br />
           <input type="text" value="<?php echo get_tags(); ?>" name="tags" size="100" />

    <br />
    <br />
        上传图片
    <input name="pic" type="text" id="pic" value=" " size="35" />
    <input name="doclear" type="button" class="rb1" id="doclear" value="清  除" onclick="javascript:document.post_form.pic.value='';"/>    <br />
    <iframe src="fileupload.php?action=article" scrolling="No" topmargin="0" width="300" height="50" marginwidth="0" marginheight="0" frameborder="0" ></iframe>

    <br />
    <br />
    <div id="editor_panel" style="width: 800px;"></div>
    <textarea style="width: 800px; height:350px;" id="editor_area" name="editor" ></textarea>
    <div id="pages-other">
      <div id="pages-other-box">
        <div class="date">
          <div style="float:left;">Year<br />
            <select  name = "year">
              <?php
                $year = range(2000,2032);
                foreach ( $year as $y )
                {
                    $str = '';
                    if ( date('Y', time()) == strval($y) )
                    {
                        $str = 'selected="selected"';
                    }
                    echo '<option value="'.$y.'" '.$str.'>'.$y.'</option>';
                }
                ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Month<br />
            <select  name = "month">
              <?php
                $month = range(1,12);
                foreach ( $month as $m )
                {
                    $str = '';
                    if ( date('m', time()) == strval($m) )
                    {
                        $str = 'selected="selected"';
                    }
                    echo '<option value="'.$m.'" '.$str.'>'.$m.'</option>';
                }
                ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Day<br />
            <select  name = "day">
              <?php
                $day = range(1,31);
                foreach ( $day as $d )
                {
                    $str = '';
                    if ( date('d', time()) == strval($d) )
                    {
                        $str = 'selected="selected"';
                    }
                    echo '<option value="'.$d.'" '.$str.'>'.$d.'</option>';
                }
                ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Hours<br />
            <select  name = "hour">
              <?php
                $hour = range(0,23);
                foreach ( $hour as $h )
                {
                    $str = '';
                    if ( date('H', time()) == strval($h) )
                    {
                        $str = 'selected="selected"';
                    }
                    echo '<option value="'.$h.'" '.$str.'>'.$h.'</option>';
                }
                ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Minutes<br />
            <select  name = "minute">
              <?php
                $minute = range(0,59);
                foreach ( $minute as $m )
                {
                    $str = '';
                    if ( date('i', time()) == strval($m) )
                    {
                        $str = 'selected="selected"';
                    }
                    echo '<option value="'.$m.'" '.$str.'>'.$m.'</option>';
                }
                ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Seconds<br />
            <select  name = "second">
              <?php
                $second = range(0,59);
                foreach ( $second as $s )
                {
                    $str = '';
                    if ( date('s', time()) == strval($s) )
                    {
                        $str = 'selected="selected"';
                    }
                    echo '<option value="'.$s.'" '.$str.'>'.$s.'</option>';
                }
                ?>
            </select>
          </div>
        </div>
        <br style="clear:both;" />
      </div>
    </div>
    <br style="clear:both;" />
    <br />
    <input type="submit" class="submit"  name="add_post" value="Save"class="wymupdate" />
  </form>
</div>
<?php
}// end add_post();

function edit_post()
{
    if ( isset($_GET['post_id']) and is_numeric(data_filter($_GET['post_id'])) )
    {
        post_do_save();
        global $db;
        $post_table = $db->get_prefix('posts');
        $sql = 'SELECT * FROM '.$post_table.' WHERE '.$post_table.'.post_id='.data_filter($_GET['post_id']).' LIMIT 0,1';
        $post = $db->sql_first_row($sql);
        if ( $post === NULL or $post === FALSE )
        {
            admin_error();
        }
        else
        {

    ?>
<div id="content">
  <div class="admin-heading">Edit post</div>
  <form action="" method="post"  >
    <input type="hidden" name="post_id" value="<?php echo $post->post_id; ?>" />
    <input type="hidden" name="user" value="<?php echo $post->post_author;?>" />
    <br />
    <label>Title</label>
    <br />
    <input type="text" value="<?php echo myhtml_entity_decode(stripslashes($post->post_title)); ?>" name="post_title" size = "100" />
    <br />
    <label>Name (slug)</label>
    <br />
    <input type="text" value="<?php echo $post->post_slug; ?>" name="post_slug" size = "100" />
    <br />
          <label>Views</label>
          <br />
          <input type="text" value="<?php echo $post->post_hit; ?>" name="post_hit" size="20" /></td>
<br />
          <label>Tags</label>
          <br />

       <input type="text" value="<?php echo get_tags($post->post_id); ?>" name="tags" size="100" />

    <br />
    <br />
    <div id="editor_panel" style="width: 800px;"></div>
    <textarea style="width: 800px; height:350px;" id="editor_area" name="editor" ><?php echo myhtml_entity_decode(stripslashes($post->post_content)); ?></textarea>
    <div id="pages-other">
      <div id="pages-other-box">
        <div class="date">
          <div style="float:left;">Year<br />
            <select  name = "year">
              <?php
                            $year = range(2000,2032);
                            foreach ( $year as $y )
                            {
                                $str = '';
                                if ( date('Y', $post->post_time) == strval($y) )
                                {
                                    $str = 'selected="selected"';
                                }
                                echo '<option value="'.$y.'" '.$str.'>'.$y.'</option>';
                            }
                            ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Month<br />
            <select  name = "month">
              <?php
                            $month = range(1,12);
                            foreach ( $month as $m )
                            {
                                $str = '';
                                if ( date('m', $post->post_time) == strval($m) )
                                {
                                    $str = 'selected="selected"';
                                }
                                echo '<option value="'.$m.'" '.$str.'>'.$m.'</option>';
                            }
                            ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Day<br />
            <select  name = "day">
              <?php
                            $day = range(1,31);
                            foreach ( $day as $d )
                            {
                                $str = '';
                                if ( date('d', $post->post_time) == strval($d) )
                                {
                                    $str = 'selected="selected"';
                                }
                                echo '<option value="'.$d.'" '.$str.'>'.$d.'</option>';
                            }
                            ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Hours<br />
            <select  name = "hour">
              <?php
                            $hour = range(0,23);
                            foreach ( $hour as $h )
                            {
                                $str = '';
                                if ( date('H', $post->post_time) == strval($h) )
                                {
                                    $str = 'selected="selected"';
                                }
                                echo '<option value="'.$h.'" '.$str.'>'.$h.'</option>';
                            }
                            ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Minutes<br />
            <select  name = "minute">
              <?php
                            $minute = range(0,59);
                            foreach ( $minute as $m )
                            {
                                $str = '';
                                if ( date('i', $post->post_time) == strval($m) )
                                {
                                    $str = 'selected="selected"';
                                }
                                echo '<option value="'.$m.'" '.$str.'>'.$m.'</option>';
                            }
                            ?>
            </select>
          </div>
          <div style="float:left;padding-left:5px">Seconds<br />
            <select  name = "second">
              <?php
                            $second = range(0,59);
                            foreach ( $second as $s )
                            {
                                $str = '';
                                if ( date('s', $post->post_time) == strval($s) )
                                {
                                    $str = 'selected="selected"';
                                }
                                echo '<option value="'.$s.'" '.$str.'>'.$s.'</option>';
                            }
                            ?>
            </select>
          </div>
        </div>
        <br style="clear:both;" />
      </div>
    </div>
    <br style="clear:both;" />
    <br />
    <input type="submit" class="submit"  name="edit_post" value="Save"class="wymupdate" />
  </form>
</div>
<?php
        }
    }
} //end edit_post();

function post_do_save()
{

    global $db;
    if ( isset($_POST['add_post']) )
    {
        $_POST = data_filter($_POST);
        $post_title = $_POST['post_title'];
        $post_slug = $_POST['post_slug'];
        $post_hit = $_POST['post_hit'];
        $post_tags = $_POST['tags'];
        $post_content = $_POST['editor'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $day = $_POST['day'];
        $hour = $_POST['hour'];
        $minute = $_POST['minute'];
        $second = $_POST['second'];
        $post_author = $_POST['user'];
        $pic = $_POST['pic'];

        $post_time = mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year);
        if ( !empty($pic) )
        {
            $post_content = '<p><img src="'.site_url().IMAGE_DIRECTORY_NAME.'/'.$pic.'" alt="'.$post_title.'" title="'.$post_title.'" /></p><p>'.$post_content.'</p>';
        }
        $data = array(
                        'post_slug'         =>      $post_slug,
                        'post_title'        =>      $post_title,
                        'post_author'       =>      $post_author,
                        'post_content'      =>      $post_content,
                        'post_time'         =>      $post_time,
                        'post_hit'          =>      $post_hit
                        );
        $result = $db->insert('posts', $data);
        if ( $result !== FALSE )
        {
            $id = $db->insert_id();
            insert_post_tags($post_tags, $id);
            admin_success();
            sitemap();
        }
        else
        {
            err_msg('reg failed..');
        }
    }

    if ( isset($_POST['edit_post']) )
    {
        $_POST = data_filter($_POST);
        $post_id = $_POST['post_id'];
        $post_title = $_POST['post_title'];
        $post_slug = $_POST['post_slug'];
        $post_hit = $_POST['post_hit'];
        $post_tags = $_POST['tags'];
        $post_content = $_POST['editor'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $day = $_POST['day'];
        $hour = $_POST['hour'];
        $minute = $_POST['minute'];
        $second = $_POST['second'];
        $post_author = $_POST['user'];

        $post_time = mktime((int)$hour, (int)$minute, (int)$second, (int)$month, (int)$day, (int)$year);

        insert_post_tags($post_tags, $post_id);

        $data = array(
                        'post_slug'         =>      $post_slug,
                        'post_title'        =>      $post_title,
                        'post_author'       =>      $post_author,
                        'post_content'      =>      $post_content,
                        'post_time'         =>      $post_time,
                        'post_hit'          =>      $post_hit
                        );

        foreach ( $data as $key => $value )
        {
            if ( !is_numeric($value) )
            {
                $value = '\''.$value.'\'';
            }
            $sql = 'UPDATE '.$db->get_prefix('posts').' SET '.$key.'='.$value.' WHERE post_id='.$post_id;
            $db->query($sql);
        }
        admin_success();
    }
}

function del_post()
{
    global $db;
    if ( isset($_GET['post_id']) and is_numeric(data_filter($_GET['post_id'])) )
    {
        $sql = 'DELETE FROM '.$db->get_prefix('posts').' WHERE post_id='.data_filter($_GET['post_id']);
        $db->query($sql);
        del_post_tags(data_filter($_GET['post_id']));
        $sql = 'DELETE FROM '.$db->get_prefix('comments').' WHERE post_id='.data_filter($_GET['post_id']);
        $db->query($sql);
        sitemap();
        redirect('?id=posts');
    }
}
?>
