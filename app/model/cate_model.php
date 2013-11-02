<?php
class Cate_model extends Model{

    public function get_list_by_cid($cid, $page_index = 0, $page_size = 10)
    {
        $post_table = $this->db->get_prefix('posts');
        $cate_table = $this->db->get_prefix('category');
        $sql = 'SELECT * FROM '.$post_table.' a LEFT JOIN '.$cate_table.' b ON (a.post_category=b.category_id) WHERE b.category_id='.$cid.' ORDER BY a.post_time DESC LIMIT '.$page_size*($page_index - 1).', '.$page_size;
        return $this->db->sql_rows($sql);
    }
    
    public function get_one_by_slug($cate_slug)
    {
        $sql = 'SELECT * FROM '.$this->db->get_prefix('category').' WHERE category_slug = \''.$cate_slug.'\' LIMIT 1';
        return $this->db->sql_first_row($sql);
    }
    
}

/* End of model.php */
