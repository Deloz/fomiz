<?php
class Post_model extends Model{
    
    public function update_hit($pid = 0)
    {
        if ( $pid > 0 )
        {
            $this->db->query('UPDATE '.$this->db->get_prefix('posts').' SET post_hit=post_hit+1 WHERE post_id='.$pid) ;
        }
    }    
    
    public function get_post_by_pid($pid = 0)
    {
        $post_table = $this->db->get_prefix('posts');
        return $this->db->sql_first_row('SELECT * FROM '.$post_table.' WHERE post_id='.$pid.' LIMIT 1');
    }
    
    public function get_comments_by_pid($pid = 0)
    {
        return $this->db->sql_rows('SELECT * FROM '.$this->db->get_prefix('comments').' WHERE post_id='.$pid.' ORDER BY comment_time ASC ');
    }
    
    public function count_comment_pid( $pid = 0 )
    {
        return $this->db->total('comments', 'post_id = '.$pid);
    }
    
    public function get_previous_post($pid = 0)
    {
        return $this->get_post_by_pid($pid - 1);
    }
    
    public function get_next_post($pid = 0 )
    {
        return $this->get_post_by_pid($pid + 1);
    }
    
    public function get_post_tags($pid = 0 )
    {
        $post_table = $this->db->get_prefix('posts');
        $post_tags_table = $this->db->get_prefix('post_tags');
        $tags_table = $this->db->get_prefix('tags');
        $sql = "SELECT {$tags_table}.* FROM {$post_tags_table} LEFT JOIN {$tags_table} ON ({$post_tags_table}.tid = {$tags_table}.tag_id ) WHERE {$post_tags_table}.pid=".$pid;
        return $this->db->sql_rows($sql);
    }
}

/* End of model.php */
