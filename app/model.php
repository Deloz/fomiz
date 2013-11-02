<?php

class Model {

    public $result = NULL;
    public $link = NULL;
    public $db = NULL;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function get_options_list()
    {
        return $this->db->result_all_table('options');
    }
    
    public function get_mostview_list($limit_num = 10)
    {
        $sql = 'SELECT * FROM '.$this->db->get_prefix('posts').' ORDER BY post_hit DESC LIMIT 0,'.$limit_num;
        return $this->db->sql_rows($sql);
    }
    
    public function get_new_post_list($limit_num = 10)
    {
        $sql = 'SELECT * FROM '.$this->db->get_prefix('posts').' ORDER BY post_time DESC LIMIT 0,'.$limit_num;
        return $this->db->sql_rows($sql);
    }
    
    public function get_new_comment_list($limit_num = 10)
    {
        $sql = 'SELECT * FROM '.$this->db->get_prefix('comments').' ORDER BY comment_time DESC LIMIT 0,'.$limit_num;
        return $this->db->sql_rows($sql);
    }
    
    public function get_nav_menu()
    {
        $i = 0;
        $str = '<li><a class="mm'.$i.'" href="'.site_url().'"><b>首页</b></a></li>';
        $sql = 'SELECT * FROM '.$this->db->get_prefix('tags').' ORDER BY tag_count DESC LIMIT 0,7';

        if ( $this->db->sql_rows($sql) !== FALSE AND $this->db->sql_rows($sql) !== NULL )
        {
            $i++;
            foreach ( $this->db->sql_rows($sql) as $tag )
            {
                $str .= '<li><a  class="mm'.$i.'" href="'.SITE_URL.'tag-'.$tag->tag_id.'" title="'.$tag->tag_name.'" ><b>'.$tag->tag_name.'</b></a></li>';
                $i++;
            }
               
        }
        return $str;   
    }
    
    public function get_category_list()
    {
        $str = '';
        if ( $this->db->result_all_table('category') !== FALSE )
        {
            foreach ( $this->db->result_all_table('category') as $cate )
            {
                $str .= '<li><a href="'.SITE_URL.$cate->category_slug.'" title="'.$cate->category_name.'" >'.$cate->category_name.'</a></li>';
            }
        }
        return $str;
    }
    
    public function count_post_cid($cid)
    {
        return $this->db->total('posts', 'post_category = '.$cid);
    }
    
    public function count_all_post()
    {
        return $this->db->total('posts');
    }
    
    public function insert_id()
    {
        return $this->db->insert_id();
    }
}

/* End of model.php */
