<?php
if ( !defined('FOMIZ_ACCESS') )
{
    echo '<script>document.location.href="login.php";</script>';
    exit(0);
}

function admin_error($msg = 'something wrong...')
{
    echo '<div id="content"><div class="message-error">'.$msg.'</div></div><div>';
}

function admin_success($msg='Changes saved')
{
    echo ' <div class="message-ok">'.$msg.'</div>';
}

function err_msg($msg)
{
    echo '<div class="message-error">'.$msg.'</div>';
}

function get_svr_info()
{
?>
<h2><div class="ico_cate"></div>概 况</h2>
<dl class="box" style="width: 48%;">
  <dt>服务器概况</dt>
  <dd>
    <table class="table">
      <tr><td width="100px">主机名</td><td><?php echo $_SERVER['SERVER_NAME'] ; ?></td></tr>
      <tr><td>服务器版本</td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ; ?></td></tr>
      <tr><td>编码</td><td><?php echo $_SERVER['HTTP_ACCEPT_ENCODING'] ; ?></td></tr>
      <tr><td>登录IP</td><td><?php echo $_SERVER['REMOTE_ADDR']; ?></td></tr>
      <tr><td>浏览器</td><td><?php echo $_SERVER['HTTP_USER_AGENT']; ?></td></tr>
    </table>
  </dd>
</dl>

<?php

}

function admin_menu()
{
    if ( isset($_GET['id']) )
    {
        $str = '';
        switch ( strtolower($_GET['id']) )
        {
            case 'system':
                $str = '<li><a href="?id=system">Settings</a></li><li><a href="?id=system&sub_id=sitemap">Sitemap</a></li><li><a href="?id=system&sub_id=users">Users</a></li><li><a href="?id=system&sub_id=backup">Backups</a></li>';
                break;
            case 'tags':
                $str = '<li><a href="?id=tags">Tags</a></li><li><a href="?id=tags&sub_id="></a></li>';
                break;
            case 'posts':
                $str = '<li><a href="?id=posts">Posts</a></li><li><a href="?id=posts&sub_id="></a></li>';
                break;
        }
        echo $str;
    }
}

function get_category($cate_name = '')
{
    global $db;
    $cate = $db->result_all_table('category');
    $str = '';
    if ( $cate !== FALSE )
    {
        foreach ( $cate as $c )
        {
            $select = '';
            if ( $cate_name !== '' and $cate_name == $c->category_name )
            {
                $select = 'selected="selected"';
            }
            $str .= '<option value="'.$c->category_id.'" '.$select.' >'.$c->category_name.'</option>';
        }
    }
    return $str;
}

function get_tags($post_id = 0)
{
    global $db;
    $post_table = $db->get_prefix('posts');
    $post_tags_table = $db->get_prefix('post_tags');
    $tags_table = $db->get_prefix('tags');
    $post_tags = '';
    if ( $post_id > 0 )
    {
        $sql = "SELECT {$tags_table}.* FROM {$post_tags_table} LEFT JOIN {$tags_table} ON ( {$post_tags_table}.tid = {$tags_table}.tag_id ) WHERE {$post_tags_table}.pid=".$post_id;
        $tags = $db->sql_rows($sql);
        if ( $tags !== FALSE )
        {
            foreach ( $tags as $tag )
            {
                if ( $post_tags == '' )
                {
                    $post_tags = $tag->tag_name;
                }
                else
                {
                    $post_tags = $post_tags.','.$tag->tag_name;
                }
            }
        }
    }

    return $post_tags;
}

function insert_post_tags($post_tags, $post_id)
{
    global $db;
    $post_tags = trim($post_tags, ',');
    $tags = preg_split('/,/', $post_tags, -1);
    
    foreach ( $tags as $tag )
    {
        if ( '' !== $tag )
        {
            $sql = 'SELECT * FROM '.$db->get_prefix('tags').' WHERE tag_name=\''.trim($tag).'\'';
            $result = $db->sql_first_row($sql);
            if ( NULL === $result or $result === FALSE )
            {
                $insert_sql = 'INSERT INTO '.$db->get_prefix('tags').' (tag_slug, tag_name) VALUES ( \''.$tag.'\',\''.$tag.'\')';
                $db->query($insert_sql);
                $insert_id = $db->insert_id();
                $insert_sql = 'INSERT INTO '.$db->get_prefix('post_tags').' (tid, pid) VALUES ('.$insert_id.','.$post_id.')';
                $db->query($insert_sql);
                $sql = 'UPDATE '.$db->get_prefix('tags').' SET tag_count=tag_count+1 WHERE tag_id='.$insert_id;
                $db->query($sql);
            }
            else
            {
                $sql = 'SELECT * FROM '.$db->get_prefix('post_tags').' WHERE pid='.$post_id.' AND tid='.$result->tag_id;
                $pt = $db->sql_first_row($sql);
                if ( $pt === FALSE or $pt === NULL)
                {
                    $insert_sql = 'INSERT INTO '.$db->get_prefix('post_tags').' (tid, pid) VALUES ('.$result->tag_id.','.$post_id.')';
                    $db->query($insert_sql);
                    $sql = 'UPDATE '.$db->get_prefix('tags').' SET tag_count=tag_count+1 WHERE tag_id='.$result->tag_id;
                    $db->query($sql);
                }
            }

        }
    }
}

function del_post_tags($pid)
{
    global $db;
    $sql = 'SELECT * FROM '.$db->get_prefix('post_tags').' WHERE pid='.$pid;
    $result = $db->sql_rows($sql);
    $sql = 'DELETE FROM '.$db->get_prefix('post_tags').' WHERE pid='.$pid;
    $db->query($sql);
    foreach ( $result as $tag )
    {
        $sql = 'UPDATE '.$db->get_prefix('tags').' SET tag_count=tag_count-1 WHERE tag_id='.$tag->tid;
        $db->query($sql);
    }
}

function admin_page($nums,$current_page,$page_size=10)
{
    $url=$_SERVER["QUERY_STRING"];
    if(stristr($url,'&page')){
        $url=preg_replace('/&page=([\S]+?)$/','',$url);
    }
    if(stristr($url,'page')){
        $url=preg_replace('/page=([\S]+?)$/','',$url);
    }
    if(!empty($url))
    {
        $url .= '&';
    }
    $total_page = ceil($nums/$page_size);
    $str = '总<span style="color:#F00">'.$nums.'</span>&nbsp;&nbsp;&nbsp;记录,&nbsp;<span style="color:#F00">'.$current_page.'/'.$total_page.'</span>&nbsp;&nbsp;&nbsp;页&nbsp;';
    if($current_page == 1 && $total_page > 1)
    {
        $str .= '首页&nbsp;';
        $str .= '<a href="?'.$url.'page='.($current_page+1).'">下一页</a>&nbsp;';
        $str .= '<a href="?'.$url.'page='.$total_page.'">尾页</a>&nbsp;';
    }
    elseif($current_page == 1 && $total_page == 1)
    {
        $str .= '首页&nbsp';
        $str .= '上一页&nbsp;';
        $str .= '下一页&nbsp;';
        $str .= '尾页&nbsp;';
    }
    elseif($current_page > 1 && 1 <$total_page && $nums > $page_size && ($current_page != $total_page))
    {
        $str .= '<a href="?'.$url.'page=1">首页</a>&nbsp;';
        $str .= '<a href="?'.$url.'page='.($current_page-1).'">上一页</a>&nbsp;';
        $str .= '<a href="?'.$url.'page='.($current_page+1).'">下一页</a>&nbsp;';
        $str .= '<a href="?'.$url.'page='.$total_page.'">尾页</a>&nbsp;';
    }
    else
    {
        $str .= '<a href="?'.$url.'page=1">首页</a>&nbsp;';
        $str .= '<a href="?'.$url.'page='.($current_page-1).'">上一页</a>&nbsp;';
        $str .= '尾页&nbsp;';
    }
    return $str;
}

function img_watermark($target_file)
{
    $error_msg = 'not support this type, please use Type: GIF, JPEG, PNG, BMP';
    if ( !empty($target_file) AND file_exists($target_file) )
    {
        list($src_w, $src_h, $type, $attr) = getimagesize($target_file);
        //1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM
        switch ( $type )
        {
            case 1:
                $src_image = imagecreatefromgif($target_file);
                break;
            case 2:
                $src_image = imagecreatefromjpeg($target_file);
                break;
            case 3:
                $src_image = imagecreatefrompng($target_file);
                break;
            default:
                die($error_msg);
    
        }
        if ( !$src_image )
        {
            die('failed...');
        }
        
        $dst_image = imagecreatetruecolor($src_w, $src_h);
        imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $src_w, $src_h, $src_w, $src_h);
        $black = imagecolorallocate($dst_image, 0, 0, 0);
        $white = imagecolorallocate($dst_image, 255, 255, 255);
        $text = 'Fomiz.com';
        $font = 'fzzyfw.ttf';
        $font_size = 15;
        $angle = 0;
        $pos_x = $src_w - 105;
        $pos_y = $src_h - 10;
        imagettftext($dst_image, $font_size, $angle, $pos_x - 1, $pos_y - 1, $black, $font, $text);
        imagettftext($dst_image, $font_size, $angle, $pos_x    , $pos_y - 1, $black, $font, $text);
        imagettftext($dst_image, $font_size, $angle, $pos_x - 1, $pos_y    , $black, $font, $text);
        imagettftext($dst_image, $font_size, $angle, $pos_x + 1, $pos_y    , $black, $font, $text);
        imagettftext($dst_image, $font_size, $angle, $pos_x + 1, $pos_y + 1, $black, $font, $text);
        imagettftext($dst_image, $font_size, $angle, $pos_x,     $pos_y + 1, $black, $font, $text);
        imagettftext($dst_image, $font_size, $angle, $pos_x,     $pos_y    , $white, $font, $text);
        $quality = 100;
        switch ( $type )
        {
            case 1:
                imagegif($dst_image, $target_file);
                break;
            case 2:
                imagejpeg($dst_image, $target_file, $quality);
                break;
            case 3:
                imagepng($dst_image, $target_file);
                break;
            default:
                die($error_msg);
        }
        imagedestroy($dst_image);
    }
    else
    {
        die('the image does not exists.');
    }

}


function upload_error_msg($msg)
{
    die('<script>alert("'.$msg.'");history.back();</script>');
}

function sitemap()
{
    global $db;
    $xml_str = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="'.site_url().'sitemap.xsl"?><!-- generator="Fomiz.com" -->
<!-- sitemap-generator-url="'.site_url().'" sitemap-generator-version="1.0.0" -->
<!-- generated-on="'.date('c',time()).'" -->
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>'.site_url().'</loc>
        <lastmod>'.date('c',time()).'</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>';
    $data = $db->sql_rows('SELECT post_id, post_time FROM '.$db->get_prefix('posts').' ORDER BY post_time DESC');
    if ( $data !== FALSE AND $data !== NULL )
    {
        foreach ( $data as $post )
        {
                $xml_str .= '
                        <url>
                            <loc>'.site_url().'show-'.$post->post_id.'.jsp</loc>
                            <lastmod>'.date('c',$post->post_time).'</lastmod>
                            <changefreq>monthly</changefreq>
                            <priority>0.2</priority>
                        </url>';
        }
    }
    $data = $db->result_all_table('tags');
    if ( $data !== FALSE AND $data !== NULL )
    {
        foreach ( $data as $tag )
        {
            $xml_str .= ' <url>    <loc>'.site_url().'tag-'.$tag->tag_id.'</loc>
        <changefreq>Weekly</changefreq>
        <priority>0.3</priority>
    </url>';
        }
    }
    $xml_str .= '</urlset>';
    
    $xml_name = ROOT_PATH.'sitemap.xml';
    if ( ! file_exists($xml_name) )
    {
        if ( ! touch($xml_name) )
        {
            err_msg('file '.$xml_name.' not exists...');
        }
    }
    if ( ! is_writable($xml_name) )
    {
        err_msg('file '.$xml_name.' can not write.. ');
    }
    else
    {
        file_put_contents($xml_name, $xml_str);
        admin_success('create sitemap success...<a href="'.site_url().'sitemap.xml" target="_blank">click to see Sitemap</a>');
    }
    
}

/* ./admin/func.php */
