<?php
class Home_model extends Model{
    
    public function get_all_post($page_index = 0, $page_size = 10)
    {
        $post_table = $this->db->get_prefix('posts');
        $post_tags_table = $this->db->get_prefix('post_tags');
        $sql = 'SELECT * FROM '.$post_table.' ORDER BY '.$post_table.'.post_time DESC LIMIT '.$page_size*($page_index - 1).', '.$page_size;
/*
        $sql = 'SELECT * FROM '.$post_table.' p RIGHT JOIN '.$post_tags_table.' pt ON (p.post_id=pt.pid) ORDER BY p.post_time DESC LIMIT '.$page_size*($page_index - 1).', '.$page_size;
*/
        return $this->db->sql_rows($sql);
    }
    
    public function get_post_tags($pid)
    {
        $post_tags_table = $this->db->get_prefix('post_tags');
        $tag_table = $this->db->get_prefix('tags');
        $sql = 'SELECT * FROM '.$tag_table.' t LEFT JOIN  '.$post_tags_table.' pt ON (t.tag_id=pt.tid) WHERE pt.pid='.$pid;
        return $this->db->sql_rows($sql);
    }    
    
}

/* End of model.php */
