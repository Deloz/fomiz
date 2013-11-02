<?php

class Comment extends Fomiz{
    
    public $data ;

    public function __construct()
    {
        $this->load_model('comment_model');
        foreach ( $this->comment_model->get_options_list() as $opt )
        {
            $this->data['options'][$opt->option_slug] = $opt->option_value;
        }
        $this->data['nav_menu'] = $this->comment_model->get_nav_menu();
    }
    
    public function index()
    {
        $input_data = json_decode(file_get_contents('php://input'));
        foreach ( $input_data as $key => $value )
        {
            $value = trim($value);
            $_POST[$key] = addslashes(htmlspecialchars($value));
        }
        $data = array('name', 'email', 'comment');
/*
        $author = 'abc';
        $email = 'deloz@deloz.net';
        $url = 'ww.qq.com';
        $comment = 'sss';
        $comment_post_id = '';
*/
        $msg = $this->is_input_empty($data);
        
        if ( $msg === NULL ) //say : all isset
        {
            $url = trim($_POST['url']);
            $url = empty($url) ? site_url() : $url;
            if ( !empty($src) && !strpos($url, '://') )
            {
                $url = 'http://'.$url;         
            }

            if ( FALSE === filter_var($_POST['name'], FILTER_SANITIZE_STRING) )
            {
                $msg = 'your name too 火星...';
            }
            elseif ( FALSE === filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )
            {
                $msg = 'email is not valid...';
            }
            elseif ( FALSE === filter_var($_POST['comment'], FILTER_SANITIZE_STRING) )
            {
                $msg = 'your comment too 火星....';
            }
            elseif ( FALSE === filter_var($_POST['comment_post_id'], FILTER_SANITIZE_STRING) )
            {
                $msg = 'fuck u ';
            }
            elseif ( !empty($url) and FALSE == filter_var($url, FILTER_VALIDATE_URL)   )
            {
                $msg = 'url is not valid';
            }
            else
            {
                $msg = NULL;
            }
            //no valid
        }

        if ( ! is_null($msg) )
        {
            $this->show_error($msg);
        }        
        else
        {
            $url = trim($_POST['url']);
            $url = empty($url) ? site_url() : $url;
            if ( !empty($src) && !strpos($url, '://') )
            {
                $url = 'http://'.$url;         
            }
            $data = array(
                    'post_id'           =>      $_POST['comment_post_id'],
                    'comment_author'    =>      $_POST['name'],
                    'comment_agent'     =>      $this->get_user_agent(),
                    'comment_content'   =>      $_POST['comment'],
                    'comment_time'      =>      time(),
                    'comment_ip'        =>      $this->get_ip(),
                    'comment_url'       =>      $url,
                    'comment_email'     =>      $_POST['email']
                    
                        );
            $msg = NULL;
            if ( get_cookie('pre_comment') == $data['post_id'].$data['comment_content'] ) 
            {
                $msg = 'Aready have the same comment, please change a word...';
            }
            elseif ( time() - get_cookie('pre_time') < 60 )
            {
                $msg = 'Limit 60s a time, waiting '.(60 - ( time() - (int)get_cookie('pre_time') )).' seconds, please...';
            }
            if ( is_null($msg) )
            {
                $set_ck = array( 'pre_comment' => $data['post_id'].$data['comment_content'],
                                 'pre_author'  => $data['comment_author'],
                                 'pre_url'     => $data['comment_url'],
                                 'pre_email'   => $data['comment_email'],
                                 'pre_time' => $data['comment_time']
                                );
                set_cookie($set_ck);
                $result = $this->comment_model->insert_data($data);
                if ( $result !== FALSE )
                {
                    $insert_id = $this->comment_model->insert_id();
                    $flow = $this->comment_model->count_commnet_by_pid($data['post_id']);
                    $this->comment_model->update_comment_count($data['post_id']);
                    $this->show_comment($insert_id, $data, $flow);
                }
                else
                {
                    $this->show_error('comment faild, please concact the Webmaster...');
                }                
            }
            else
            {
                $this->show_error($msg);
            }
          
        }

    }
    
    public function get_ip()
    {
        $ip = NULL;
        if ( isset($_SERVER['REMOTE_ADDR']) )
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if ( $ip === NULL or !filter_var($ip, FILTER_VALIDATE_IP) )
        {
            $ip = '0.0.0.0';
        }
        return $ip;
    }
    
    public function get_user_agent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : NULL;
    }
    
    public function is_input_empty($in)
    {
        $msg = NULL;
        if ( is_array($in) )
        {
            foreach ( $in as $key )
            {
                $val = isset($_POST[$key]) ? trim($_POST[$key]) : '';
                if ( !isset($val) or empty($val) )
                {
                    $msg .= 'Please enter your '.$key.'.<br />';
                }      
            }
        }
        else
        {
            $val = trim($_POST[$in]);
            if ( !isset($val)  or empty($val) )
            {
                $msg = 'Please enter your '.$in.'.';
            }
        }
        return $msg;
    }
    
    public function show_error($msg)
    {
        ?><div class="comment-list"><ol>        <li class="comment"><p style="color:#f00;font-weight:bold;"><?php echo $msg; ?></p></li>
            </ol></div>
        <?php
    }
    
    public function show_comment($id, $data, $flow)
    {
        ?><div class="comment-list"><ol>       
        <li class="comment" id="comment-<?php echo $id; ?>">
          <div class="comment-author">
            <img src="<?php echo get_gravatar($data['comment_email']); ?>" class="avatar">
            <cite class="fn">
              <a href="javascript:location.href='<?php echo $data['comment_url']; ?>'" rel="external nofollow" class="url" style="color:<?php echo com_color(); ?>"><?php echo $data['comment_author']; ?></a>
            </cite>
            <span class="flow" style="color:<?php echo com_color(); ?>"><?php if($flow==1){echo "沙发";}elseif($flow==2){echo "板凳";}elseif($flow==3){echo "地板";}else{echo "第".$flow."楼";}?></span>
          </div>
          <div class="comment-meta commentmetadata">
            <?php echo com_link($data['post_id'], $id, convert_time($data['comment_time'])); ?>
          </div>
          <div class="comment-content">
            <p><?php echo str_decode($data['comment_content']); ?></p>
          </div>

          <p style="color:#f00;font-size:12px;font-weigth:bold;">comment success...</p>
        </li> </ol></div>
        <?php
    }

}

/* End of file ../app/ctrl/comment.php */
