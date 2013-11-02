<?php

/***  functions  start *****************/
function fomiz_serialize($obj)
{
    return base64_encode(gzcompress(serialize($obj)));
}

function fomiz_unserialize($text)
{
    return unserialize(gzuncompress(base64_decode($text)));
}

function success_msg()
{
}

function error_msg($err_msg = '')
{
    echo '<!DOCTYPE HTML><html><head><meta charset="utf-8"><title>The Page Not Found...</title></head><body><img src="'.site_url().'ui/images/error_404_bg.jpg" /><p><a href="'.site_url().'" />返回首页</p></body></html>';
    exit;
}

function get_comment_link($post_title, $post_id, $comment_count)
{
    return '<a href="'.SITE_URL.'show-'.$post_id.'.jsp#comments" title="有'.$comment_count.'条关于 '.$post_title.' 的评论">'.$comment_count.'</a>';
}

function com_link($post_id, $com_id, $com_title)
{
    return '<a href="'.SITE_URL.'show-'.$post_id.'.jsp#comment-'.$com_id.'">'.$com_title.'</a>';
}

function com_color()
{
    $bgcolor = array("#D66103","#512DBD","#780E1A","#C5A200","#DA4912","#530752","#C5A200","#512DBD","#D66103","#530752");
    return $bgcolor[rand(0, 9)];
}

function get_gravatar($email = '' ,$size = 80, $default = 'mm', $r = 'g')
{
    if ( $default === 'mm' )
    {
        $default = GRAVATAR;
    }    
    return 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?d='.urlencode($default).'&s='.$size.'&r=g';
}

function convert_time($date_time)
{
    return date('Y年m月d日 H:i:s', $date_time);
}

function get_post_link($post_title, $post_id)
{
    return '<a href="'.SITE_URL.'show-'.$post_id.'.jsp" title="'.$post_title.'">'.$post_title.'</a>';
}

function get_cate_link($cate_title, $cate_slug)
{
    return '<a href="'.SITE_URL.$cate_slug.'" title="'.$cate_title.'">'.$cate_title.'</a>';
}

function get_tag_link($tag_id, $tag_name)
{
    return '<a href="'.SITE_URL.'tag-'.$tag_id.'" title="'.$tag_name.'">'.$tag_name.'</a>';
}

function site_url()
{
    return SITE_URL;
}

function current_uri()
{
    $uri = explode('/', trim(strtolower($_SERVER['REQUEST_URI']), '/'));
    if ( strtolower(trim(SITE_URL,'/')) !== strtolower(trim(DOMAIN,'/')) )
    {
       array_shift($uri);
    }
    return $uri;
}

function parse_route()
{
    global $route;

    $uri = implode('/', current_uri());

    $arry_uri = array();
    foreach ( $route as $key => $val )
    { 
        $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key)); 
        if ( preg_match('#^'.$key.'$#', $uri) )
        {
            if ( strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
            {
                $val = preg_replace('#^'.$key.'$#', $val, $uri);
            }
            $array_uri =  explode('/', $val); 
            if ( count($array_uri) > 0 )
            {
                break;
            }          
        }
    }    
    
    $array_uri['ctrl'] = isset($array_uri[0]) ? $array_uri[0] : 'home';
    $array_uri['method'] = isset($array_uri[1]) ? $array_uri[1] : 'index';
    $array_uri['arg'] = isset($array_uri[2]) ? $array_uri[2] : 'NULL';
    return $array_uri;
}

function uri_splice($arr = '', $replace, $offset, $len = 1)
{
    if ( is_array($arr) )
    {
        array_splice($arr, $offset, $len, array($replace));
    }
    
    return SITE_URL.implode('/', $arr);
}

function str_decode($str)
{  
    $str = str_replace(chr(32), '&nbsp;', $str);
    $str = str_replace(array("\r\n", "\r", "\n"), '<br />', $str);
    return $str;
}

    
function get_cookie($key)
{
    if ( isset($_COOKIE[COOKIE_PORTIONS.$key]) 
          and ( base64_decode($_COOKIE[COOKIE_PORTIONS.$key]) != site_url() ) )
    {
        return base64_decode($_COOKIE[COOKIE_PORTIONS.$key]);
    }
    else
    {
        return '';
    }
    
}

function set_cookie(array $data = array(), $m = TRUE)
{
    if ( is_array($data) )
    {
        foreach ( $data as $key => $val )
        {
            setcookie(COOKIE_PORTIONS.$key, base64_encode($val));
        }
    }
}

if ( !function_exists('redirect') )
{
    function redirect($url)
    {
        if ( headers_sent() )
        {
            echo '<script>document.location.href="'.$url.'";</script>';
        }
        else
        {
            header('Location: '.$url);
        }
        exit(0);
    }
}

function encrypt_password($password, $key='delozfomiz')
{
    $pw_array = str_split($password, strlen($password)/2);
    return sha1($pw_array[0].$key.$pw_array[1]);
}

function data_filter($data)
{
    $data = preg_replace('#<script(.*?)</script>#ise', 'htmlspecialchars("$0")', $data);
    if ( is_array($data) )
    {
        foreach ( $data as $key => $value )
        {
            $data[$key] = addslashes(trim($value));
        }
    }
    else
    {
        $data = addslashes(trim($data));
    }
    
    return $data;
}

function myhtml_entity_decode($data)
{
    $data = preg_replace('@&lt;script(.*?)&lt;/script&gt;@ise', 'html_entity_decode("$0")', $data);
    $data = preg_replace('#<script(.*?)</script>#ise', 'htmlspecialchars("$0")', $data);
    return $data;
}

/***  functions end   ******************/



/* End of file  ./app/func.php */