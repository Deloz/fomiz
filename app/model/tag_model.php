<?php
class Tag_model extends Model{
    
    public function get_list_by_tid($tid, $page_index = 0, $page_size = 10)
    {
        $post_table = $this->db->get_prefix('posts');
        $post_tags_table = $this->db->get_prefix('post_tags');
        $sql = "SELECT * FROM {$post_table} p LEFT JOIN {$post_tags_table} pt ON ( p.post_id=pt.pid ) WHERE pt.tid={$tid} ORDER BY p.post_time DESC LIMIT ".$page_size*($page_index - 1).', '.$page_size;
        return $this->db->sql_rows($sql);
    }
    
    public function get_one_by_tid($tid)
    {
        $sql = 'SELECT * FROM '.$this->db->get_prefix('tags').' WHERE tag_id = '.$tid.' LIMIT 1';
        return $this->db->sql_first_row($sql);        
    }
    
    public function count_post_tid($tid)
    {
        return $this->db->total('post_tags', 'tid = '.$tid);
    }
}

/* End of ../app/model/comment_model.php */
