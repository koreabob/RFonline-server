<?php session_start(); ?>
<a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a>
<?php

###############################################################################
#                        RFServer in Korea, Made by Sweet                     #
###############################################################################

ob_start();

$temp_filename = realpath(__FILE__);
$sbox_path = eregi_replace("index.php","",$temp_filename);

$do = $_REQUEST['do'];
$gm = $_REQUEST['gm'];
$act = $_REQUEST['act'];
$page = $_REQUEST['page'];

include('config.php');
include('functions/functions.php');
LoadConfig();
include('language/'.$config[Language].'.php');
LoadPage($do,$gm);
include('skin/'.$config[Skin].'/Design.php');

$scripts = $_SERVER['PHP_SELF'];
$scripts = explode(chr(47), $scripts);
$mescript = $scripts[count($scripts)-1];

$skin_dir = 'skin/'.$config[Skin];

$ip = $_SERVER['REMOTE_ADDR'];
$isuser=false;
$notuser=true;
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
$time = time();

if ($HTTP_SESSION_VARS["sbox_logged_id"]) {

	connectuserdb();
	$query = mssql_query("SELECT userId,userPwd,CONVERT(char, LastLoginDt, 120) FROM tblUser WHERE userId='$HTTP_SESSION_VARS[sbox_logged_id]'");
	$query = mssql_fetch_array($query);
	$qrusername = trim($query['userId']);
	$qrpassword = trim($query['userPwd']);
	$qrlastlogindt= getdatetime($query[2]);

	$cashdb_query = mssql_query("SELECT * FROM tblUserInfo WHERE userId='$HTTP_SESSION_VARS[sbox_logged_id]'");
	$cashdb_query = mssql_fetch_array($cashdb_query);
	$mycash = $cashdb_query[cashBalance];

	$isuser=true;
	$notuser=false;

	// Check Admin
	connectcashdb();
	$qradmin = mssql_query("SELECT * FROM tbl_Admin_Permissions WHERE Account = '$qrusername'");
	$admin = mssql_fetch_array($qradmin);
	if (!empty($admin)) {
		$isadmin = 1;
	} else {
		$isadmin = 0;
	}

	// Check User Online Now
	if ($isadmin == "1") {
		$isonline = 0;
	} else {
		connectuser2db();
		$check_online_query = mssql_query("SELECT * FROM tbl_UserAccount WHERE id = CONVERT(binary, '$qrusername')");
		$check_online = mssql_fetch_array($check_online_query);

		$isonline = $check_online[Online];
	}


	if (($do == "") && ($gm == "")) {
		$upt_nowpage = '*';
	} else {
		$upt_nowpage = $do.$gm;
	}

	// 로그인 시간이 지정된 시간을 넘었거나 로그인 아이피가 현재 사용자의 아이피와 다를 경우 로그아웃 시킴
	if(time()-$HTTP_SESSION_VARS["sbox_logged_time"]>$config[SessionTime]||$HTTP_SESSION_VARS["sbox_logged_ip"]!=$ip) {

		$isuser=false;
		$notuser=true;

		$sbox_logged_id="";
		$sbox_logged_time="";
		$sbox_logged_ip="";
		session_register("sbox_logged_id");
		session_register("sbox_logged_ip");
		session_register("sbox_logged_time");
		session_destroy();

	// 유효할 경우 로그인 시간을 다시 설정
	} else {
		$isuser=true;
		$notuser=false;

		// 4.0x 용 세션 처리
		$sbox_logged_time=time();
		session_register("sbox_logged_time");

		connectcashdb();
		mssql_query("UPDATE tbl_Session SET SessionTime = '$time', NowPage = '$upt_nowpage' WHERE userId = '$qrusername'");
	}

	connectcashdb();
	$check_session_query = mssql_query("SELECT * FROM tbl_Session WHERE userId = '$qrusername'");
	$check_session = mssql_fetch_array($check_session_query);

	mssql_query("DELETE tbl_Session WHERE ($time - SessionTime) > ($config[RefreshTime] + 10)");

	if (empty($check_session)) {
		mssql_query("INSERT tbl_Session (userId, Ip, SessionTime, NowPage, Admin) VALUES ('$qrusername', '$ip', '$time', '$upt_nowpage', '$isadmin')");
	}

} 

require_once('skin/'.$config[Skin].'/header.php');

if (($do == "") && ($notuser)) {
?>
		<table border="0" align="center">
		<form name="form" method="post" action="login.php">
		<tr>
			<td colspan="2"><p style="padding: 2px; text-align: center; font-weight: bold;"><?=$msg_welcome_notice?></p></td>
		</tr>
		<tr>
			<td colspan="2" height="20"></td>
		</tr>
		<tr>
			<td align="right"><b><?=$txt_userid?>&nbsp;:&nbsp;</b></td>
			<td><input type="text" name="username" class="input_login"></td>
		</tr>
		<tr>
			<td align="right"><b><?=$txt_userpw?>&nbsp;:&nbsp;</b></td>
			<td><input type="password" name="password" class="input_login"></td>
		</tr>
		<tr>
			<td colspan="2" height="10"></td></tr>
		<tr>
			<td align="center" colspan="2"><input class="btn_login" type="submit" value="<?=$btn_login?>" onclick="sboxLoad[1].start()"></td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
		</form>
		</table>
<?
} elseif (($gm == "") && ($do == "") && ($isuser)) {

	connectdatadb();
	$msg_query = mssql_query("SELECT * FROM tbl_GreetMsg WHERE useType < 255");
?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align="center">
			<table class="tborder" border="0" cellpadding="3" cellspacing="1" width="100%">
            <tr>
            <td colspan="2">
            <a href="?do=downloads" class="btn_yellow"><span>Downloads</span><a href="index.php?do=vote" class="btn_yellow"><span>Vote for Reward</span></a><a href="index.php?do=sexchange" class="btn_yellow"><span>Sex Change</span></a>
            </td>
            </tr>
			<tr>
				<td class="thead" style="padding: 8px;" colspan="2">Archons Greeting Message</td>
			</tr>
<?
	while($msg = mssql_fetch_array($msg_query)) {

		if ($msg[useType] == "0") {
			$msg_races = $txt_race_b;
		} elseif ($msg[useType] == "1") {
			$msg_races = $txt_race_c;
		} elseif ($msg[useType] == "2") {
			$msg_races = $txt_race_a;
		}
?>
			<tr>
				<td class="alt2" style="padding: 4px;"><img src="images/race_mark/icon_mark_<?=$msg[useType]?>.gif" border="0" align="absmiddle">&nbsp;<?=$msg_races?></td>
				<td class="alt1" style="padding: 4px;"><?=substr($msg[GMsg], 0, -12)?></td>
			</tr>
<?
	}
?>
			</table>
			</td>
		</tr>
		<tr>
			<td align="center">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center"><? print_sbox('Sbox_Board_Notice', $msg_welcome, 5, 45, 300, 'Y/m/d') ?></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
<?
} else {
	if (($gm != "") && ($isadmin == "1")) {
		if (file_exists("page/admin/$gm.php")) {
			include_once('page/admin/'.$gm.'.php');
		} else {
			include_once('functions/page_not_found.php');
		}

	} elseif (($gm != "") && ($isadmin == "0")) {

		connectcashdb();
		mssql_query("INSERT INTO tbl_User_Trace (Account, IP) VALUES ('$qrusername', '$ip')");
?>
		<table border="0" align="center">
		<tr>
			<td align="center" class="warning"><?=$msg_warning?></td>
		</tr>
		<tr>
			<td align="center"><?=$msg_warning_gmpage?></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td align="center"><a href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		</table>
<?
	} else {
		if (file_exists("page/$do.php")) {
			include_once("page/$do.php");
		} else {
			include_once('functions/page_not_found.php');
		}
	}
}

require_once('skin/'.$config[Skin].'/footer.php');

?>