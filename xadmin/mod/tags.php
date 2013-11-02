<?php

if ( !defined('FOMIZ_ACCESS') )
{
    echo '<script>document.location.href="login.php";</script>';
    exit(0);
}

if ( isset($_GET['action']) )
{
    switch ( $_GET['action'] )
    {
        case 'edit':
            edit_tag();
            break;
        case 'add':
            add_tag();
            break;
        case 'delete':
            del_tag();
            break;
        default:
            admin_error();
    }
}
else
{
    show_tag();
}

function show_tag()
{    
    global $db;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page_size = 10;
    $sql = 'SELECT * FROM '.$db->get_prefix('tags').' LIMIT '.($page-1)*$page_size.','.$page_size;  $tag = $db->sql_rows($sql);
    $nums = $db->total('tags');
    ?>

<div id="content">
    <?php
               tag_do_save();
                ?>
    <script type="text/javascript">
    function confirmDelete(msg){var data=confirm(msg+"?"); return data;}
    </script>
  <div id="section-bar"> <span class="btn"><a href="?id=tags&action=add" title="Add new tag">Add tag</a></span> </div>
  <div style="clear:both"></div>
  <table class="admin-table">
    <thead class="admin-table-header">
      <tr>
        <td class="admin-table-field">tag name</td>
        <td class="admin-table-field">tag slug</td>
        <td align="left">post count</td>
        <td></td>
      </tr>
    </thead>
    <tbody class="admin-table-content">
      <?php if ( $tag !== FALSE ) :
      foreach ( $tag as $t ): ?>
      <tr class="admin-table-tr">
        <td class="admin-table-field"><?php echo $t->tag_name; ?></td>
        <td class="admin-table-field"><?php echo $t->tag_slug; ?></td>
        <td class="admin-table-field"><?php echo $t->tag_count; ?></td>
        <td class="admin-table-field" align="right"><span class="btn-edit"><a href="?id=tags&action=edit&tag_id=<?php echo $t->tag_id; ?>" title="edit">Edit</a></span> <span class="btn-delete"><a href="?id=tags&action=delete&tag_id=<?php echo $t->tag_id; ?>" title="delete" onclick="return confirmDelete('Delete')">Delete</a></span></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
  <br />
  <?php  echo admin_page($nums, $page, $page_size);?> <!-- page -->
</div>
<?php  // end show_tag();
}

function edit_tag()
{
    global $db;
    if ( isset($_GET['tag_id']) and is_numeric($_GET['tag_id']) )
    {
        tag_do_save();
      
        $tag = $db->sql_first_row('SELECT * FROM '.$db->get_prefix('tags').' WHERE tag_id='.trim($_GET['tag_id']));
        if ( $tag !== NULL and $tag !== FALSE )
        {
        ?>
            <div id="content">
              <div class="admin-heading">Edit tag</div>
                
                <form action="" method="post">
              <input type="hidden" name="tag_id" value="<?php echo $tag->tag_id; ?>" />
              <br />
              <label>Tag name</label>
              <br />
              <input type="text" name="tag_name" value="<?php echo $tag->tag_name; ?>" size="100" />
              <br />
              <label>Tag slug</label>
              <br />
              <input type="text" name="tag_slug" value="<?php echo $tag->tag_slug; ?>" size="100" />

             <br />
                <br />
                <input type="submit" name="edit_tag" class="submit" value="Save" />
                </form>
            </div>
<?php
        }
        else
        {
            admin_error();
        }
        
    }
    else
    {
        admin_error();
    }// end edit_tag();
}

function add_tag()
{
    ?>
            <div id="content">
              <div class="admin-heading">Add Tag</div>
                <?php
               tag_do_save();
                ?>
                <form action="" method="post">
              <br />
              <label>Tag name</label>
              <br />
              <input type="text" name="tag_name" value="" size="100" />
              <br />
              <label>Tag slug</label>
              <br />
              <input type="text" name="tag_slug" value="" size="100" />
             <br />
                <br />
                <input type="submit" name="add_tag" class="submit" value="Save" />
                </form>
            </div>
    <?php
}

function del_tag()
{
    global $db;
    if ( isset($_GET['tag_id']) )
    {
        $_GET = data_filter($_GET);
        if ( is_numeric($_GET['tag_id']) )
        {
            $sql = 'DELETE FROM '.$db->get_prefix('tags').' WHERE tag_id='.$_GET['tag_id'];
            $db->query($sql);
            $sql = 'DELETE FROM '.$db->get_prefix('post_tags').' WHERE tid='.$_GET['tag_id'];
            $db->query($sql);
            sitemap();
            redirect('?id=tags');          
        }
        else
        {
            admin_error();
        }
    }
}

function tag_do_save()
{   
    global $db;
    if ( isset($_POST['edit_tag']) )
    {
        $_POST = data_filter($_POST);
        $tag_name = $_POST['tag_name'];
        $tag_slug = $_POST['tag_slug'];
        $tag_id = $_POST['tag_id'];

        if ( '' === $tag_name )
        {
            err_msg('tag name could not empty...');
        }
        else
        {
            $sql = 'UPDATE '.$db->get_prefix('tags').' SET tag_slug=\''.$tag_slug.'\', tag_name=\''.$tag_name.'\' WHERE tag_id='.$tag_id;
            $db->query($sql);
            admin_success();
        }
    }
    
    if ( isset($_POST['add_tag']) )
    {
        $_POST = data_filter($_POST);
        $tag_name = $_POST['tag_name'];
        $tag_slug = $_POST['tag_slug'];

        if ( '' === $tag_name )
        {
            err_msg('tag name could not empty...');
        }
        else
        {
            if ( '' === $tag_slug )
            {
                $tag_slug = $tag_name;
            }
        
            $data = array( 'tag_slug'          =>      $tag_slug,
                           'tag_name'          =>      $tag_name
            );
            $sql = 'SELECT * FROM '.$db->get_prefix('tags').' WHERE tag_name=\''.$tag_name.'\'';
            if ( FALSE === $db->sql_rows($sql) )
            {
                    
                $result = $db->insert('tags', $data);
                if ( $result !== FALSE )
                {
                    admin_success();
                }
                else
                {
                    err_msg('reg failed..');
                }
            }
            else
            {
                err_msg('already exists...');
            }
        }
    }
}
?>
