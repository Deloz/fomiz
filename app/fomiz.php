<?php

class Fomiz {

    public $uri = array();
    public $model;

    public function __construct($uri)
    {
        $this->uri = $uri;       
    }

    public function load_ctrl($class_name)
    {
        $file_name = CTRL_PATH.$this->uri['ctrl'].EXT;
        $this->is_file_exists($file_name);

        $ctrl = new $class_name();
        unset($file_name);
        if ( method_exists($ctrl, $this->uri['method']) )
        {
            $ctrl->{$this->uri['method']}($this->uri['arg']);
        }
        else
        {
            $ctrl->index();
        }
    }

    public function load_view($view, $args = '')
    {
        if ( is_array($args) && count($args) > 0 )
        {
            extract($args, EXTR_PREFIX_SAME, 'fmz');
        }
        $file_array = array('header_view', $view, 'footer_view');
        foreach ( $file_array as $file_name)
        {
            $file_name = VIEW_PATH.$file_name.EXT;
            if ( !file_exists($file_name) )
            {
                error_msg('File '.$file_name.' Not found.');
            }
            require_once($file_name);     
        }

    }

    public function load_model($model)
    {
        $file_name = MODEL_PATH.$model.EXT;
        $this->is_file_exists($file_name);

        $this->$model = new $model;
    }

    public function is_file_exists($file_name)
    {
        if ( !file_exists($file_name) )
        {
            error_msg('File '.$file_name.' Not found.');
        }
        require_once($file_name);
    }
    
    public function uri_segment($id=0)
    {
        $uri = current_uri();

        return isset($uri[$id]) ? $uri[$id] : NULL;
    }
    
    public function pagination_links($params = array())
    {
        $pagination['base_url'] = '';
        $pagination['total_rows'] = '';
        $pagination['per_page'] = 10;
        $pagination['cur_page'] = 0;
        $pagination['uri_segment'] = 3;
        if ( count($params) > 0 )
        {
            foreach ( $params as $key => $val )
            {
                if ( isset($pagination[$key]) )
                {
                    $pagination[$key] = $val;
                }
            }
        }

        if ( $pagination['total_rows'] == 0 OR $pagination['per_page'] == 0 )
        {
            return '';
        }
        
        $num_pages = ceil($pagination['total_rows'] / $pagination['per_page']);

        if ( $pagination['cur_page'] > $num_pages )
        {
            error_msg('Oop, page not found.' );
        }
        
        $output = '<div class="pagination">总<span style="color:#F00">'.$pagination['total_rows'].'</span>记录,&nbsp;<span class="page-number">'.$pagination['cur_page'].'/'.$num_pages.'</span>页&nbsp;';
                
        if ( $pagination['cur_page'] == 1 && $num_pages > 1 )
        {
            $output .= '首页&nbsp;';
            $output .= '<span class="next-page"><a href="'.uri_splice($pagination['base_url'], ($pagination['cur_page'] + 1), $pagination['uri_segment']).'">下一页</a></span>';
            $output .= '<span class="last-page"><a href="'.uri_splice($pagination['base_url'], ($num_pages), $pagination['uri_segment']).'">尾页</a></span>';
        }
        elseif ( $pagination['cur_page'] == 1 && $num_pages == 1 )
        {
            $output .= '首页&nbsp;上一页&nbsp;下一页&nbsp;尾页&nbsp';
        }
        elseif ( $pagination['cur_page'] > 1 
                && 1 < $num_pages 
                && $pagination['total_rows'] > $pagination['per_page']
                && ( $pagination['cur_page'] != $num_pages ) )
        {
            $output .= '<span class="first-page"><a href="'.uri_splice($pagination['base_url'], 1, $pagination['uri_segment'] ).'">首页</a></span>';
            $output .= '<span class="previous-page"><a href="'.uri_splice($pagination['base_url'], ($pagination['cur_page'] - 1), $pagination['uri_segment']).'">上一页</a></span>';
            $output .= '<span class="next-page"><a href="'.uri_splice($pagination['base_url'], ($pagination['cur_page'] + 1), $pagination['uri_segment']).'">下一页</a></span>';
            $output .= '<span class="last-page"><a href="'.uri_splice($pagination['base_url'], $num_pages, $pagination['uri_segment']).'">尾页</a></span>';
        }
        else
        {
            $output .= '<span class="first-page"><a href="'.uri_splice($pagination['base_url'], 1, $pagination['uri_segment']).'">首页</a></span>';
            $output .= '<span class="previous-page"><a href="'.uri_splice($pagination['base_url'], ($pagination['cur_page'] - 1), $pagination['uri_segment']).'">上一页</a></span>';
            $output .= '尾页&nbsp;';
        }
        return $output.'</div>';
    }
}

/* End of file fomiz.php */