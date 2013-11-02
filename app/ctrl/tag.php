<?php

class Tag extends Fomiz{
        
    public $data ;

    public function __construct()
    {
        $this->load_model('tag_model');
        foreach ( $this->tag_model->get_options_list() as $opt )
        {
            $this->data['options'][$opt->option_slug] = $opt->option_value;
        }
        $this->data['nav_menu'] = $this->tag_model->get_nav_menu();
    }
    public function index()
    {
        ////////////////////
        $mark_segment = 2;
        $page_segment = 1;
        $tag_segment = 0;
        ///////////////////////////////////

        $mark = $this->uri_segment($mark_segment);
        if ( isset($mark) )
        {
            error_msg('this page not ...');
        }
        $page_index = $this->uri_segment($page_segment);//page_index
        if ( NULL === $page_index )
        {
            $page_index = 1;
        }
        else if ( !is_numeric($page_index)  or $page_index === '0')
        {
            error_msg('this page not exists in the tag.....');
        }
        
        $tag_id = preg_replace('#^tag-([\d]+)$#', "$1", $this->uri_segment($tag_segment));
        if ( strpos($tag_id, '0') === 0 )
        {
            error_msg('something error');
        }
        
        $tag = $this->tag_model->get_one_by_tid($tag_id);
        if ($tag === NULL  or  $tag === FALSE )
        {
            error_msg('this tag not exists ');
        }
        
        $this->data['tag_id'] = $tag->tag_id;
        
        $page['per_page'] = $this->data['options']['pagesize'];
        $page['cur_page'] = $page_index;
        $page['base_url'] = current_uri();
        $page['uri_segment'] = $page_segment;
        $page['total_rows'] = $this->tag_model->count_post_tid($tag->tag_id);
        $this->data['page_str'] = $this->pagination_links($page);
            
        $this->data['posts'] = $this->tag_model->get_list_by_tid($tag->tag_id, $page_index, $this->data['options']['pagesize']);
        $this->data['webtitle'] = $tag->tag_name.' - 第'.$page_index.'页 - '.$this->data['options']['webname'];
            
        ////////////////////////////////
        $this->data['mostview'] = $this->tag_model->get_mostview_list($this->data['options']['mostviewnum']);
        $this->data['newposts'] = $this->tag_model->get_new_post_list($this->data['options']['newpostnum']);
        $this->data['newcomments'] = $this->tag_model->get_new_comment_list($this->data['options']['newcomnum']);
        ///////////////////////////////
           
        $this->load_view('tag_view', $this->data);
    
    }


}

/* End of file cate.php */