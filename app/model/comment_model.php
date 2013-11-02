<?php
class Comment_model extends Model{

    public function insert_data($data)
    {
        return $this->db->insert('comments', $data);
    }
    
    public function update_comment_count($post_id)
    {
        return $this->db->query('UPDATE '.$this->db->get_prefix('posts').' SET post_comment_count=post_comment_count+1 WHERE post_id='.$post_id);
    }
    
    public function count_commnet_by_pid($pid)
    {
        return $this->db->total('comments','post_id='.$pid);
    }
}

/* End of ../app/model/comment_model.php */
