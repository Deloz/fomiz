<?php
/*****************************************
 * @Author: Deloz
 * **************************************/

error_reporting(E_ALL);

session_start();

require_once('config.php');

//parse routes..
$array_uri = parse_route();

$app = new Fomiz($array_uri);
$app->load_ctrl($array_uri['ctrl']);



/* End of file index.php  */