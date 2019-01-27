<?php

###############################################################################
#                        RFServer in Korea, Made by Sweet                     #
###############################################################################

ob_start();

include('config.php');
include('functions/functions.php');
LoadConfig();
include('language/'.$config[Language].'.php');
include('skin/'.$config[Skin].'/Design.php');

$skin_dir = 'skin/'.$config[Skin];

$ip = $_SERVER['REMOTE_ADDR'];

require_once('skin/'.$config[Skin].'/header.php');

connectcashdb();
mssql_query("DELETE tbl_Session WHERE userId = '$sbox_logged_id'");


//setcookie ("userdata", "", time() - 3600);


// Unregister Session
$sbox_logged_id='';
$sbox_logged_time='';
$sbox_logged_ip='';
$sbox_secret='';
$sbox_last_connect_check = '0';

session_register("sbox_logged_id");
session_register("sbox_logged_time");
session_register("sbox_logged_ip");
session_register("sbox_secret");
session_register("sbox_last_connect_check");
session_destroy(); 

?>
		<? movepage($config[FileName]); ?>
<?
require_once('skin/'.$config[Skin].'/footer.php');

?>