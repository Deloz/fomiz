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
            edit_category();
            break;
        case 'add':
            add_category();
            break;
        case 'delete':
            del_category();
            break;
        default:
            admin_error();
    }
}
else
{
    show_category();
}

function show_category()
{    
    global $db;
    $cate = $db->result_all_table('category');
    ?>

<div id="content">
    <script type="text/javascript">
    function confirmDelete(msg){var data=confirm(msg+" ?"); return data;}
    </script>
  <div id="section-bar"> <span class="btn"><a href="?id=category&action=add" title="Register new user">Add category</a></span> </div>
  <div style="clear:both"></div>
  <table class="admin-table">
    <thead class="admin-table-header">
      <tr>
        <td class="admin-table-field">category name</td>
        <td class="admin-table-field">category slug</td>
        <td align="left">category description</td>
        <td></td>
      </tr>
    </thead>
    <tbody class="admin-table-content">
      <?php if ( $cate !== FALSE ) :
      foreach ( $cate as $c ): ?>
      <tr class="admin-table-tr">
        <td class="admin-table-field"><?php echo $c->category_name; ?></td>
        <td class="admin-table-field"><?php echo $c->category_slug; ?></td>
        <td class="admin-table-field"><?php echo $c->category_description; ?></td>
        <td class="admin-table-field" align="right"><span class="btn-edit"><a href="?id=category&action=edit&category_id=<?php echo $c->category_id; ?>" title="edit">Edit</a></span> <span class="btn-delete"><a href="?id=category&action=delete&category_id=<?php echo $c->category_id; ?>" title="delete" onclick="return confirmDelete('Delete')">Delete</a></span></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>
<?php  // end show_category();
}

function edit_category()
{
    global $db;
    if ( isset($_GET['category_id']) and is_numeric($_GET['category_id']) )
    {
        if (cate_do_save()) 
        {
            admin_success();
        }
        $cate = $db->sql_first_row('SELECT * FROM '.$db->get_prefix('category').' WHERE category_id='.trim($_GET['category_id']));
        if ( $cate !== NULL and $cate !== FALSE )
        {
        ?>
            <div id="content">
              <div class="admin-heading">Edit category</div>
                
                <form action="" method="post">
              <input type="hidden" name="cate_id" value="<?php echo $cate->category_id; ?>" />
              <br />
              <label>Category name</label>
              <br />
              <input type="text" name="cate_name" value="<?php echo $cate->category_name; ?>" size="100" />
              <br />
              <label>Category slug</label>
              <br />
              <input type="text" name="cate_slug" value="<?php echo $cate->category_slug; ?>" size="100" />
              <br />
              <label>Category description</label>
              <br />
              <input type="text" name="cate_des" value="<?php echo $cate->category_description; ?>" size="100" />
             <br />
                <br />
                <input type="submit" name="edit_cate" class="submit" value="Save" />
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
    }// end edit_category();
}

function add_category()
{
    ?>
            <div id="content">
              <div class="admin-heading">Add category</div>
                <?php
               cate_do_save();
                ?>
                <form action="" method="post">
              <br />
              <label>Category name</label>
              <br />
              <input type="text" name="cate_name" value="" size="100" />
              <br />
              <label>Category slug</label>
              <br />
              <input type="text" name="cate_slug" value="" size="100" />
              <br />
              <label>Category description</label>
              <br />
              <input type="text" name="cate_des" value="" size="100" />
             <br />
                <br />
                <input type="submit" name="add_cate" class="submit" value="Save" />
                </form>
            </div>
    <?php
}

function del_category()
{
    global $db;
    if ( isset($_GET['category_id']) )
    {
        if ( is_numeric($_GET['category_id']) )
        {
            $sql = 'DELETE FROM '.$db->get_prefix('category').' WHERE category_id='.$_GET['category_id'];
            $db->query($sql);
            redirect('?id=category');          
        }
        else
        {
            admin_error();
        }
    }
}

function cate_do_save()
{   
    global $db;
    if ( isset($_POST['edit_cate']) )
    {
        $cate_name = data_filter($_POST['cate_name']);
        $cate_slug = data_filter($_POST['cate_slug']);
        $cate_des  = data_filter($_POST['cate_des']);
        $cate_id   = data_filter($_POST['cate_id']);
        
        if ( '' === $cate_name )
        {
            err_msg('category name could not empty...');
        }
        elseif ( '' === $cate_slug )
        {
            err_msg('category slug could not be empty...');
        }
        else
        {
            $sql = 'UPDATE '.$db->get_prefix('category').' SET category_slug=\''.$cate_slug.'\',category_description=\''.$cate_des.'\',category_name=\''.$cate_name.'\' WHERE category_id='.$cate_id;
            $db->query($sql);
            admin_success();
        }
    }
    
    if ( isset($_POST['add_cate']) )
    {
        $cate_name = data_filter($_POST['cate_name']);
        $cate_slug = data_filter($_POST['cate_slug']);
        $cate_des  = data_filter($_POST['cate_des']);

        if ( '' === $cate_name )
        {
            err_msg('category name could not empty...');
        }
        elseif ( '' === $cate_slug )
        {
            err_msg('category slug could not be empty...');
        }
        else
        {
            $data = array( 'category_slug'          =>      $cate_slug,
                           'category_name'          =>      $cate_name,
                           'category_description'   =>      $cate_des
                        );
            $result = $db->insert('category', $data);
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
}
?>
