<div  id="wrap">
  <div id="content">
    <?php if ( $posts === FALSE ) : ?>
    <div class="entry">
      <h2>暂时没有文章</h2>
      <h3>还没更新...</h3>
      <p>请耐心等待</p>
    </div>
    <?php else: ?>
    <?php foreach ( $posts as $post ) : ?>
    <div class="entry">
      <h2><?php echo get_post_link($post->post_title, $post->post_id); ?></h2>
      <div class="postmeta"><span class="posttime"><?php echo convert_time($post->post_time); ?></span><span class="postcmt"><?php echo get_comment_link($post->post_title, $post->post_id, $post->post_comment_count); ?></span><span class="postview"><?php echo $post->post_hit; ?></span></div>
      <div class="post"><?php echo $post->post_content; ?>
        <p>标签:<?php echo $tags[$post->post_id]; ?></p>
      </div>
    </div>
    <?php endforeach; ?>
    <?php echo $page_str; ?>
    <?php endif;?>
  </div>
  <div id="sidebar">
    <div class="sidenotie"> <b class="fmz1"></b><b class="fmz2"></b><b class="fmz3"></b><b class="fmz4"></b><b class="fmz5"></b><b class="fmz6"></b><b class="fmz7"></b>
      <div class="fomizcontent">
        <?php echo $options['notice']; ?>
      </div>
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
