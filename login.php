<?php session_start(); ?>
<a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a><a href="nolink.php"></a>
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
$username = $_POST['username'];
$password = $_POST['password'];
$notuser=true;
$isuser=false;
$time = time();

require_once('skin/'.$config[Skin].'/header.php');
?>
		<table border="0" align="center">
<?
if (($username == "") && ($password == "")) {
?>
		<tr>
			<td align="center" class="warning"><?=$msg_warning?></td>
		</tr>
		<tr>
			<td align="center"><?=$msg_empty?></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td align="center"><a href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td>
		</tr>
<?	
} else {

	if (eregi("[^a-zA-Z0-9_-]", $password)) {
?>
		<tr>
			<td align="center" class="warning"><?=$msg_warning?></td>
		</tr>
		<tr>
			<td align="center"><?=$msg_wrong_pw?></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td align="center"><a href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td>
		</tr>
<?		
	} else {
		$username = ereg_replace( ";$", "", $username);
		$username = ereg_replace( "\\\\", "", $username);
		$password = ereg_replace( ";$", "", $password);
		$password = ereg_replace( "\\\\", "", $password);
		$username = strtolower(antiject($username));
		$password = antiject($password);

		connectuserdb();
		$qruser_chk = mssql_query("SELECT * FROM tblUser WHERE userId = '$username'");
		$user_chk = mssql_fetch_array($qruser_chk);

		$qrusername = trim($user_chk['userId']);
		$qrpassword = trim($user_chk['userPwd']);

		connectuser2db();
		$qrlogin_chk = mssql_query("SELECT * FROM tbl_UserAccount WHERE id = CONVERT(binary, '$qrusername')");
		$login_chk = mssql_fetch_array($qrlogin_chk);

		$qrstaff_login_chk = mssql_query("SELECT * FROM tbl_StaffAccount WHERE ID = CONVERT(binary, '$qrusername')");
		$staff_login_chk = mssql_fetch_array($qrstaff_login_chk);

		if (!empty($login_chk)) {
			$qrban_chk = mssql_query("SELECT * FROM tbl_UserBan WHERE nPeriod = 999 AND nAccountSerial = $login_chk[serial]");
			$ban_chk = mssql_fetch_array($qrban_chk);
		}

		if (!empty($user_chk)) {

			if ((!empty($login_chk)) || (!empty($staff_login_chk))) {

				if (!empty($ban_chk)) {
?>
		<tr>
			<td align="center" class="warning"><?=$msg_warning?></td>
		</tr>
		<tr>
			<td align="center"><?=$msg_ban_user?></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td align="center"><a href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td>
		</tr>
<?
				} else {

				if (($username == strtolower($qrusername)) && ($password == $qrpassword)) {

					//$cookievalue = $username.chr(255).md5(strtolower($username).$ip.$qrpassword);
					//setcookie("userdata", $cookievalue);


					// 4.0x 侩 技记 贸府
					$_SESSION['sbox_logged_id'] = $qrusername;
					$_SESSION['sbox_logged_time'] = time();
					$_SESSION['sbox_logged_ip'] = $REMOTE_ADDR;
					$_SESSION['sbox_last_connect_check'] = '0';
		 
					connectuserdb();
					mssql_query("UPDATE tblUser SET lastLoginDt = getdate() WHERE userId = '$qrusername'");

					$check_usernum_query = mssql_query("SELECT * FROM tblUser WHERE userId = '$qrusername'");
					$check_usernum = mssql_fetch_array($check_usernum_query);

					$check_usernum_info_query = mssql_query("SELECT * FROM tblUserInfo WHERE userId = '$qrusername'");
					$check_usernum_info = mssql_fetch_array($check_usernum_info_query);

					if (!empty($check_usernum)) {
						if (!empty($check_usernum_info)) {
							if ($check_usernum[userNumber] != $check_usernum_info[userNumber]) {
								mssql_query("UPDATE tblUserInfo SET userNumber = '$check_usernum[userNumber]' WHERE userId = '$qrusername'");
							}
						} else {
							mssql_query("INSERT INTO tblUserInfo (userNumber,userId,userPwd,email) VALUES ('$usernum','$userid','$userpw','$email')");
						}
					}
	echo '
<center><img src="./ajax-loader.gif"></center>
<script type="text/javascript">
<!--
window.location = "index.php"
//-->
</script>
';
				} else {
?>
		<tr>
			<td align="center" class="warning"><?=$msg_warning?></td>
		</tr>
		<tr>
			<td align="center"><?=$msg_wrong_login?></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td align="center"><a href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td>
		</tr>
<?
				}	

				}
			} else {
?>
		<tr>
			<td align="center" class="warning"><?=$msg_warning?></td>
		</tr>
		<tr>
			<td align="center"><?=$msg_not_login?></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td align="center"><a href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td>
		</tr>
<?
			}
		} else {
?>
		<tr>
			<td align="center" class="warning"><?=$msg_warning?></td>
		</tr>
		<tr>
			<td align="center"><?=$msg_register_no_exist?></td>
		</tr>
		<tr>
			<td height="15"></td>
		</tr>
		<tr>
			<td align="center"><a href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td>
		</tr>
<?
		}
	}
}
?>
		<tr>
			<td height="15"></td>
		</tr>
		</table>
<?
require_once('skin/'.$config[Skin].'/footer.php');

?>