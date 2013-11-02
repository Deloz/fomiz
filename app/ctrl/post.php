<?php

class Post extends Fomiz{
    
    public $data;
    
    public function __construct()
    {
        $this->load_model('post_model');
        foreach ( $this->post_model->get_options_list() as $opt )
        {
            $this->data['options'][$opt->option_slug] = $opt->option_value;
        }
        $this->data['category'] = $this->post_model->get_category_list();
        $this->data['nav_menu'] = $this->post_model->get_nav_menu();
    }
    
    public function show($pid)
    {
        //mark
        $mark_segment = 1;
        $post_segment = 0;
        ///////////
        $pid = intval($pid);
        $mark = $this->uri_segment($mark_segment);
        if ( isset($mark) )
        {
            error_msg('this post not ...');
        }
        if ( $pid <= 0 )
        {
            error_msg('not post...');
        }
        else
        {
            $this->post_model->update_hit($pid);
            $post = $this->post_model->get_post_by_pid($pid);
            $this->data['post'] = $post;
            if ( $post === NULL or $post === FALSE )
            {
                error_msg('not post...');
            }
            $this->data['webtitle'] = $post->post_title.' - '.$this->data['options']['webname'];
            $this->data['comments'] = $this->post_model->get_comments_by_pid($pid);
            $this->data['total_comments'] = $this->post_model->count_comment_pid($pid);
            
            $previous_post = $this->post_model->get_previous_post($pid);
            $next_post = $this->post_model->get_next_post($pid);
            if ( is_null($previous_post) )
            {
                $this->data['previous_post_link'] = '<a href="javascript:void(0);">没有上一篇了...</a>';
            }
            else
            {
                $this->data['previous_post_link'] = get_post_link($previous_post->post_title, $previous_post->post_id);
            }
            if ( is_null($next_post) )
            {
                $this->data['next_post_link'] = '<a href="javascript:void(0);">没有下一篇了...</a>';
            }
            else
            {
                $this->data['next_post_link'] = get_post_link($next_post->post_title, $next_post->post_id);
            }
        
            ////////////////////////////////
            //最多点击
            $this->data['mostview'] = $this->post_model->get_mostview_list($this->data['options']['mostviewnum']);
            //最新文章
            $this->data['newposts'] = $this->post_model->get_new_post_list($this->data['options']['newpostnum']);
            //最新评论
            $this->data['newcomments'] = $this->post_model->get_new_comment_list($this->data['options']['newcomnum']);
            //标签
            $this->data['tm_tags'] = $this->post_model->get_post_tags($pid);
            $post_tags = 'No Tags';
            if ( $this->data['tm_tags'] !== FALSE )
            {
                foreach ( $this->data['tm_tags'] as $tag )
                {
                    if ( $post_tags == 'No Tags' )
                    {
                        $post_tags = get_tag_link($tag->tag_id, $tag->tag_name);
                    }
                    else
                    {
                        $post_tags = $post_tags.', '.get_tag_link($tag->tag_id, $tag->tag_name);
                    }
                }
            }
            $this->data['tags'] = $post_tags;
            
            ///////////////////////////////
             
            $this->load_view('post_view', $this->data);
        }
    }
}

/* End of file post.php */