<?
ob_start();
?>

		<script type="text/javascript">
			function onLoaded(obj) {
				var res = obj.responseText
				document.getElementById("chk_id_text").innerHTML = res
			}
			function getMsg(obj) {
				sendRequest(
					onLoaded,
					'&data='+obj.form.userid.value,
					'POST',
					'./functions/ajax_id_check.php',
					true,
					true
				)
			}
		</script>
<?php

###############################################################################
#                        RFServer in Korea, Made by Sweet                     #
###############################################################################

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

require_once('skin/'.$config[Skin].'/header.php');

if ($config[RegOpen] == "True") {

	$act = $_REQUEST['act'];

	if ($act == "") {
?>
		<?=$skin_table?>

		<tr>
			<td class="alt1"><p style="font-weight: bold; text-align: center; padding: 2px;"><?=$msg_register_notice?></p></td>
		</tr>
		</table>
		<form method="post" action="<?=$PHP_SELF?>?act=confirm" id="formID">
		<?=$skin_table?>

		<tr>
			<td class="alt2" width="30%">&nbsp;<?=$txt_userid?>&nbsp;:&nbsp;</td>
			<td class="alt1" width="70%" style="line-height: 100%"><input type="text" name="userid" class="validate[optional,custom[noSpecialCaracters],length[0,20]]" autocomplete="off" onkeyup="getMsg(this)" style="ime-mode: disabled;">&nbsp;&nbsp;<span id="chk_id_text"></span></td>
		</tr>
		<tr>
			<td class="alt2" width="30%">&nbsp;<?=$txt_userpw?>&nbsp;:&nbsp;</td>
			<td class="alt1" width="70%"><input type="text" name="userpw" class="validate[required,length[6,11]] text-input" style="ime-mode: disabled;"></td>
		</tr>
		<tr>
			<td class="alt2" width="30%">&nbsp;<?=$txt_userrepw?>&nbsp;:&nbsp;</td>
			<td class="alt1" width="70%"><input type="text" name="userrepw" class="validate[required,confirm[password]] text-input" style="ime-mode: disabled;"></td>
		</tr>
		<tr>
			<td class="alt2" width="30%">&nbsp;<?=$txt_email?>&nbsp;:&nbsp;</td>
			<td class="alt1" width="70%"><input type="text" name="email" class="validate[required,custom[email]] text-input" style="ime-mode: disabled;"></td>
		</tr>
		<tr>
			<td class="alt1" align="center" colspan="2"><input class="btn_list" type="submit" value="<?=$btn_register?>" onclick="sboxLoad[1].start()">&nbsp;&nbsp;<input class="btn_list" type="reset" value="<?=$btn_reset?>"></td>
		</tr>
		</table>
<?
	} elseif ($act == "confirm"){

		include_once('functions/sql_check.php');
		check_inject();

		connectuserdb();
		$userid = stripslashes($_POST['userid']);
		$userpw = stripslashes($_POST['userpw']);
		$userrepw = stripslashes($_POST['userrepw']);
		$email = stripslashes($_POST['email']);
?>
		<table border="0" align="center">
<?
		if ((eregi("[^a-zA-Z0-9_-]", $userid)) ||
		(eregi("[^a-zA-Z0-9_-]", $userpw)) ||
		(eregi("[^a-zA-Z0-9_-]", $userrepw)) ||
		(eregi("[^a-zA-Z0-9\.@_-]", $email))) {
?>
			<tr><td align="center" class="warning"><?=$msg_warning?></td></tr>
			<tr><td align="center"><?=$msg_inject?></td></tr>
			<tr><td height="15"></td></tr>
			<tr><td align="center"><a class="normalfont" href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td></tr>
<?
		} else {

			$sql_username_check = mssql_query("SELECT userId FROM tblUser WHERE userId='$userid'");
			$username_check = mssql_num_rows($sql_username_check);

			$sql_email_check = mssql_query("SELECT email FROM tblUserInfo WHERE email='$email'");
			$email_check = mssql_num_rows($sql_email_check);

			if (empty($userid) || empty($userpw) || empty($userrepw) || empty($email)) {
?>
				<tr><td align="center" class="warning"><?=$msg_warning?></td></tr>
				<tr><td align="center"><?=$msg_empty?></td></tr>
				<tr><td height="15"></td></tr>
				<tr><td align="center"><a class="normalfont" href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td></tr>
<?
				$Error=1;
			} elseif ($username_check > 0) {
?>
				<tr><td align="center" class="warning"><?=$msg_warning?></td></tr>
				<tr><td align="center"><?=$msg_register_failure_id?></td></tr>
				<tr><td height="15"></td></tr>
				<tr><td align="center"><a class="normalfont" href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td></tr>
<?
				$Error=1;
			} elseif ($userpw != $userrepw) {
?>
				<tr><td align="center" class="warning"><?=$msg_warning?></td></tr>
				<tr><td align="center"><?=$msg_register_failure_pw_nomatch?></td></tr>
				<tr><td height="15"></td></tr>
				<tr><td align="center"><a class="normalfont" href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td></tr>
<?
				$Error=1;
			} elseif ($email_check > 0) {
?>
				<tr><td align="center" class="warning"><?=$msg_warning?></td></tr>
				<tr><td align="center"><?=$msg_register_failure_email?></td></tr>
				<tr><td height="15"></td></tr>
				<tr><td align="center"><a class="normalfont" href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td></tr>
<?
				$Error=1;
			}

			if ($Error!=1){

				$checkid = mssql_query("SELECT * FROM tblUser WHERE userId='$userid'");
				$checkid = mssql_fetch_array($checkid);

				if (!empty($checkid)) {
?>
					<tr><td align="center" class="warning"><?=$msg_warning?></td></tr>
					<tr><td align="center"><?=$msg_register_failure_id?></td></tr>
					<tr><td height="15"></td></tr>
					<tr><td align="center"><a class="normalfont" href="#" onclick="javascript:history.back(-1);"><?=$btn_backpage?></a></td></tr>
<?
				} else {
					mssql_query("INSERT INTO tblUser (userId,userPwd,gameServiceId) VALUES ('$userid','$userpw',6)");

					$query = mssql_query("SELECT * FROM tblUser WHERE userId='$userid'");
					$query = mssql_fetch_array($query);
					$usernum = $query['userNumber'];
					mssql_query("INSERT INTO tblUserInfo (userNumber,userId,userPwd,gameServiceId,email,cashBalance) VALUES ('$usernum','$userid','$userpw','6','$email','1000')");

					connectuser2db();
					mssql_query("INSERT INTO tbl_rfaccount (id,password,accounttype,birthdate,Email) VALUES (CONVERT(binary,'$userid'),CONVERT(binary,'$userpw'),'0','$today','$email')");
?>
		<? movepage($config[FileName]); ?>
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
	}

} else {
?>
		<p align="center"><?=$msg_register_close?></p>
<?
}

require_once('skin/'.$config[Skin].'/footer.php');
?>