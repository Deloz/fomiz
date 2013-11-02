<div  id="wrap">
  <div id="content">
    <?php if ( $post === FALSE or $post === NULL ) : ?>
    <div class="entry">
      <h2>暂时没有文章</h2>
      <h3>还没更新...</h3>
      <p>请耐心等待</p>
    </div>
    <?php else: ?>
    <div class="entry">
      <h2><?php echo get_post_link($post->post_title, $post->post_id); ?></h2>
      <div class="postmeta"><span class="posttime"><?php echo convert_time($post->post_time); ?></span><span class="postcmt"><?php echo get_comment_link($post->post_title, $post->post_id, $post->post_comment_count); ?></span><span class="postview"><?php echo $post->post_hit; ?></span></div>
      <div class="post"><?php echo $post->post_content; ?>
       <p>标签:<?php echo $tags; ?></p>
      </div>
    </div>
    <div id="addtion">
      <div class="post-nav"><p>上一篇:<?php echo $previous_post_link; ?></p><p> 下一篇:<?php echo $next_post_link; ?></p></div>
    </div>
    <div id="comments">
      <?php if ( $comments === FALSE ):?>
      <h3>没有关于&quot;<?php echo $post->post_title; ?>&quot;的评论</h3>
      <?php else: ?>
      <h3><?php echo $total_comments;?>条关于&quot;<?php echo $post->post_title; ?>&quot;的评论</h3>
      <div class="comment-list">
        <ol>
          <?php
          $flow = count($comments);
          foreach ( $comments as $com ) : ?>
          <li class="comment" id="comment-<?php echo $com->comment_id;?>">
            <div class="comment-author"><img  src="<?php echo get_gravatar($com->comment_email); ?>" class="avatar" /> <cite class="fn"> <a href="javascript:location.href='<?php echo $com->comment_url; ?>'" rel="external nofollow" class="url" style="color:<?php echo com_color(); ?>"><?php echo $com->comment_author; ?></a> </cite><span class="flow" style="color:<?php echo com_color(); ?>"><?php if($flow==1){echo "沙发";}elseif($flow==2){echo "板凳";}elseif($flow==3){echo "地板";}else{echo "第".$flow."楼";}?></span></div>
            <div class="comment-meta commentmetadata"><?php echo com_link($com->post_id, $com->comment_id, convert_time($com->comment_time)); ?></div>
            <div class="comment-content">
              <p><?php echo str_decode($com->comment_content); ?></p>
            </div>
          </li>
          <?php 
          $flow++;
          endforeach; ?>
          
        </ol>
      </div>
      <?php endif; ?><div id="textHint"></div>
      <script type="text/javascript">
        <!--
        var accessUrl  = '<?php echo site_url(); ?>comment.jsp';
        //-->
      </script>
      <script type="text/javascript" src="<?php echo site_url(); ?>/ui/js/fomiz.js"></script>
      
      <div id="respond">
        <h3 id="reply-title">发表评论</h3>
        <form   method="post" id="commentform" name="commentform">
          <p class="comment-form-author">
            <input id="author" name="name" type="text" value="<?php echo get_cookie('pre_author'); ?>" size="30" tabindex="1" />
            <label for="author">Name</label>
            <span class="required">*</span></p>
          <p class="comment-form-email">
            <input id="email" name="email" type="text" value="<?php echo get_cookie('pre_email'); ?>" size="30" tabindex="2" aria-required="true" />
            <label for="email">Email</label>
            <span class="required">*</span> </p>
          <p class="comment-form-url">
            <input id="url" name="url" type="text" value="<?php echo get_cookie('pre_url'); ?>" size="30" tabindex="3" />
            <label for="url">Website</label>
          </p>
          <p class="comment-form-comment">
            <textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>
          </p>
          <p class="form-submit">
            <input name="submit" type="button" id="submit" value="写好,发表..." style="opacity: 1" onclick="checkData()" />
            <input type="hidden" name="comment_post_id" value="<?php echo $post->post_id; ?>" id="comment_post_id" />
          </p>
          
        </form>
      </div>
    </div>
    <?php endif;?>
  </div>
  <div id="sidebar">
    <div class="sidenotie"> <b class="fmz1"></b><b class="fmz2"></b><b class="fmz3"></b><b class="fmz4"></b><b class="fmz5"></b><b class="fmz6"></b><b class="fmz7"></b>
      <div class="fomizcontent"> <?php echo $options['notice']; ?> </div>
      <b class="fmz7"></b><b class="fmz6"></b><b class="fmz5"></b><b class="fmz4"></b><b class="fmz3"></b><b class="fmz2"></b><b class="fmz1"></b> <em></em><span></span> </div>
    <img src="<?php echo site_url(); ?>/ui/images/profile.png" width="230" height="204" />
    <div class="widget"><a version="1.0" class="qzOpenerDiv" href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_likeurl" target="_blank">喜欢就点一下...</a><script  src="http://ctc.qzonestyle.gtimg.cn/qzone/app/qzlike/qzopensl.js#jsdate=20110603&style=1&showcount=1&width=130&height=30" charset="utf-8" defer="defer" ></script></div>
    <div class="widget">
      <h3>最新</h3>
      <ul>
        <?php if ( $newposts === FALSE ) :?>
        暂时没有
        <?php else: ?>
        <?php foreach ( $newposts as $view ) :?>
        <li>[&nbsp;<?php echo date('m-d', $view->post_time); ?>&nbsp;]&nbsp;<?php echo get_post_link($view->post_title, $view->post_id); ?></li>
        <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
    <div class="widget">
      <h3>最热</h3>
      <ul>
        <?php if ( $mostview === FALSE ) :?>
        暂时没有
        <?php else: ?>
        <?php foreach ( $mostview as $view ) :?>
        <li>[&nbsp;<?php echo $view->post_hit; ?>&nbsp;]&nbsp;<?php echo get_post_link($view->post_title, $view->post_id); ?></li>
        <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
    <div class="widget">
      <h3>最新评论</h3>
      <ul>
        <?php if ( $newcomments === FALSE ) :?>
        暂时没有
        <?php else: ?>
        <?php foreach ( $newcomments as $com ) :?>
        <li><?php echo $com->comment_author; ?>: <?php echo com_link($com->post_id, $com->comment_id, $com->comment_content); ?></li>
        <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>
