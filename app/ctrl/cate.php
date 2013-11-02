<?php

class Cate extends Fomiz{
        
    public $data ;

    public function __construct()
    {
        $this->load_model('cate_model');
        foreach ( $this->cate_model->get_options_list() as $opt )
        {
            $this->data['options'][$opt->option_slug] = $opt->option_value;
        }
        $this->data['nav_menu'] = $this->cate_model->get_nav_menu();
    }
    public function index()
    {
        ////////////////////
        $mark_segment = 2;
        $page_segment = 1;
        $cate_slug_segment = 0;
        ///////////////////////////////////

        $mark = $this->uri_segment($mark_segment);
        if ( isset($mark) )
        {
            error_msg('this page not ...');
        }
        $page_index = $this->uri_segment($page_segment);//mark
        if ( is_null($page_index) )
        {
            $page_index = 1;
        }
        else if ( !is_numeric($page_index)  or $page_index === '0')
        {
            error_msg('this page not exists in the category.....');
        }
        
        $cate_slug = $this->uri_segment($cate_slug_segment); 
        $cate = $this->cate_model->get_one_by_slug($cate_slug);
        $this->data['cate'] = $cate;
        if ( $cate === FALSE or is_null($cate) )
        {
            error_msg('this uri not exists........................');
        }
        else
        {
            $this->data['cate_id'] = $cate->category_id;
            
            $page['per_page'] = $this->data['options']['pagesize'];
            $page['cur_page'] = $page_index;
            $page['base_url'] = current_uri();
            $page['uri_segment'] = $page_segment;
            $page['total_rows'] = $this->cate_model->count_post_cid($cate->category_id);
            $this->data['page_str'] = $this->pagination_links($page);
            
            $this->data['posts'] = $this->cate_model->get_list_by_cid($cate->category_id, $page_index, $this->data['options']['pagesize']);
            $this->data['webtitle'] = $cate->category_name.' - 第'.$page_index.'页 - '.$this->data['options']['webname'];
            
            ////////////////////////////////
            $this->data['mostview'] = $this->cate_model->get_mostview_list($this->data['options']['mostviewnum']);
            $this->data['newposts'] = $this->cate_model->get_new_post_list($this->data['options']['newpostnum']);
            $this->data['newcomments'] = $this->cate_model->get_new_comment_list($this->data['options']['newcomnum']);
            ///////////////////////////////
            
            $this->load_view('cate_view', $this->data);
        }       
    }

    public function add($title='')
    {
        $this->load_view('', $data);
    }
}

/* End of file cate.php */