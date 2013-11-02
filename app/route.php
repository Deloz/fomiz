<?php
/*****************************************
 * @Author: Deloz
 * **************************************/

$route['(:num)'] = 'home/index';
$route['show-(:num).jsp'] = 'post/show/$1';
$route['tag-(:num)'] = 'tag/index';
$route['tag-(:num)/(:num)'] = 'tag/index';
$route['comment.jsp'] = 'comment';
$route['(:any)'] = 'cate/index';

/* End of file ../app/route.php */
