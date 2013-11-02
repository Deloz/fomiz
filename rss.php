<?php
/*****************************************
 * @Author: Deloz
 * **************************************/

error_reporting(E_ALL);
require_once('config.php');
$db = DB::getInstance();
$opt = array();
foreach ( $db->result_all_table('options') as $o )
{
    $opt[$o->option_slug] = $o->option_value;
}
$last_post_time  =  $db->sql_first_row('SELECT post_time FROM '.$db->get_prefix('posts').'');
$pub_date = date("Y-m-d\TH:i:s\Z", time());
if ( $last_post_time !== FALSE AND $last_post_time !== NULL )
{
    $pub_date = date("Y-m-d\TH:i:s\Z", $last_post_time->post_time);
}

?><?php echo "<?xml version='1.0' encoding='utf-8' ?>\n"; ?>
<feed xml:lang="zh-CN" xmlns="http://www.w3.org/2005/Atom">
  <title><?php echo $opt['webname']; ?></title>
  <subtitle><?php echo $opt['description']; ?></subtitle>
  <link href="<?php echo site_url(); ?>rss.php" rel="self"/>
  <updated><?php echo $pub_date; ?></updated>
  <author>
   <name><?php echo $opt['webname']; ?></name>
   <email>qq@fomiz.com</email>
  </author>
  <id>tag:fomiz.com,2011:<?php echo site_url(); ?>rss.php</id>
  <?php $data = $db->sql_rows('SELECT * FROM '.$db->get_prefix('posts').' ORDER BY post_time DESC LIMIT 0,10');
if ( $data !== FALSE AND $data !== NULL ) :
    foreach ( $data as $post ) : ?>
  <entry>
  <title><?php echo $post->post_title; ?></title>
   <link type='text/html' href='<?php echo site_url().'show-'.$post->post_id.'.jsp'; ?>' />
   <id>tag:fomiz.com,2008:<?php echo site_url().'show-'.$post->post_id.'.jsp'; ?></id>
   <updated><?php echo date("Y-m-d\TH:i:s\Z", $post->post_time); ?></updated>
   <author>
    <name><?php echo $opt['webname'];?></name>
   </author>
   <summary type="xhtml">
	<div xmlns="http://www.w3.org/1999/xhtml">
<?php echo $post->post_content; ?>
	</div>  
</summary>
   <content type="xhtml">
<div xmlns="http://www.w3.org/1999/xhtml">
<?php echo $post->post_content; ?>
</div>
</content>
  </entry>
 <?php endforeach; endif; ?>
</feed>
