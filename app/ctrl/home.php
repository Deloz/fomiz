<?php

class Home extends Fomiz{
    
    public $data ;

    public function __construct()
    {
        $this->load_model('home_model');
        foreach ( $this->home_model->get_options_list() as $opt )
        {
            $this->data['options'][$opt->option_slug] = $opt->option_value;
        }
        $this->data['nav_menu'] = $this->home_model->get_nav_menu();
    }
    public function index()
    {
        /////////////
        $mark_segment = 1;
        $page_segment = 0;
        /////////////
        $mark = $this->uri_segment($mark_segment);
        if ( isset($mark) )
        {
            error_msg('this page not ...');
        }
        $page_index = $this->uri_segment($page_segment);//mark
       
        if ( NULL === $page_index OR $page_index === '' )
        {
            $page_index = 1;
        }
        else if ( !is_numeric($page_index)  or $page_index === '0')
        {
            error_msg('this page not exists in the home.....');
        }

        $page['per_page'] = $this->data['options']['pagesize'];
        $page['cur_page'] = $page_index;
        $page['base_url'] = current_uri();
        $page['uri_segment'] = $page_segment;
        $page['total_rows'] = $this->home_model->count_all_post();
        $this->data['page_str'] = $this->pagination_links($page);
         
        $this->data['webtitle'] = ($page_index == 1) ? '首页 - '.$this->data['options']['webname'] : '第'.$page_index.'页 - '.$this->data['options']['webname'];
        $this->data['posts'] = $this->home_model->get_all_post($page_index, $this->data['options']['pagesize']);
        if ( $this->data['posts'] !== FALSE OR $this->data['posts'] !== NULL )
        {
            foreach ( $this->data['posts'] as $post )
            {
                $tm_tags = $this->home_model->get_post_tags($post->post_id);
                $post_tags = 'No Tags';
                if ( $tm_tags !== FALSE )
                {
                    foreach ( $tm_tags as $tag )
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
                $this->data['tags'][$post->post_id] = $post_tags;
            }
        }
        
        ////////////////////////////////
        $this->data['mostview'] = $this->home_model->get_mostview_list($this->data['options']['mostviewnum']);
        $this->data['newposts'] = $this->home_model->get_new_post_list($this->data['options']['newpostnum']);
        $this->data['newcomments'] = $this->home_model->get_new_comment_list($this->data['options']['newcomnum']);
        ///////////////////////////////
        
        $this->load_view('home_view', $this->data);
    }
    
}

/* End of file home.php */