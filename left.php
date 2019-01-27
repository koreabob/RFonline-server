	<tr>
		<td class="tcat" style="padding: 6px;"><a href="<?=$config[FileName]?>"><?=$config[ProgramName]?></a></td>
	</tr>

<?
if ($skinp == "") {
	connectcashdb();

if ($notuser) {
	$ucate_query = mssql_query("SELECT * FROM tbl_TopMenu_Category WHERE TopMenu = '0' and [Use] = '1' and Login = 'False' ORDER BY Preference ASC");
	while($ucate_row = mssql_fetch_array($ucate_query)) {
		if ($ucate_row[LinkType] == "1") {
?>
	<tr>
		<td nowrap="nowrap" class="alt2"><a class="smallfont" href="<?=$ucate_row[Link]?>" target="<?=$ucate_row[Target]?>"><?=$ucate_row[Name]?></a></td>
	</tr>
<?
		} else {
?>
	<tr>
		<td nowrap="nowrap" class="alt2"><a class="smallfont" href="<?=$config[FileName]?>?<?=$ucate_row[Type]?>=<?=$ucate_row[FileName]?>"><?=$ucate_row[Name]?></a></td>
	</tr>
<?
		}
	}
} else {
	$ncate_query = mssql_query("SELECT * FROM tbl_TopMenu_Category WHERE TopMenu = '0' and [Use] = '1' and Login = 'True' ORDER BY Preference DESC");
	while($ncate_row = mssql_fetch_array($ncate_query)) {
?>
	<tr>
		<td nowrap="nowrap" class="alt2"><a class="smallfont" href="<?=$ncate_row[Link]?>" target="<?=$ncate_row[Target]?>"><?=$ncate_row[Name]?></a></td>
	</tr>
<?
	}

	$qrauth = mssql_query("SELECT * FROM tbl_Admin_Permissions WHERE Account = '$qrusername'");
	$auth = mssql_fetch_array($qrauth);
	$permissions = explode(",",$auth[Permissions]);

	for ($i=0; $i<sizeof($permissions); $i++) {
		$qrcate = mssql_query("SELECT * FROM tbl_TopMenu_Category WHERE FileName = '$permissions[$i]'");
		while($cate = mssql_fetch_array($qrcate)) {
			$qrtop = mssql_query("SELECT * FROM tbl_TopMenu WHERE Num = '$cate[TopMenu]'");
			while($top = mssql_fetch_array($qrtop)) {
				$topnum[] = $top[Num];
			}
		}
	}
	$top_query = mssql_query("SELECT * FROM tbl_TopMenu WHERE Name <> 'None' and [Use] = '1' ORDER BY Name");
	while($top_row = mssql_fetch_array($top_query)) {

		$cate_num_query = mssql_query("SELECT COUNT(*) FROM tbl_TopMenu_Category WHERE TopMenu = '$top_row[Num]' and [Use] = '1'");
		$cate_num = mssql_fetch_row($cate_num_query);

		if (($top_row[Type] == "0") && ($cate_num[0] > 0)) {
?>
	<tr>
		<td class="thead">&nbsp;<?=$top_row[Name]?></td>
	</tr>
<?
		}
		if (($auth[AdminType] == "1") && ($cate_num[0] > 0)) {
			if ($top_row[Type] != "0") {
?>
	<tr>
		<td class="thead">&nbsp;<?=$top_row[Name]?></td>
	</tr>
<?
			}
		} else {
			for ($i=0; $i<sizeof($topnum); $i++) {
				if (eregi("$topnum[$i]","$top_row[Num]")) {
?>
	<tr>
		<td class="thead">&nbsp;<?=$top_row[Name]?></td>
	</tr>
<?				break;
				}
			}
		}
		$cate_query = mssql_query("SELECT * FROM tbl_TopMenu_Category WHERE TopMenu = '$top_row[Num]' and [Use] = '1' and Login = 'True' ORDER BY Preference ASC");
		while($cate_row = mssql_fetch_array($cate_query)) {
			if ($cate_row[Preference] <= "4") {
				$cate_name = '<b>'.$cate_row[Name].'</b>';
			} else {
				$cate_name = $cate_row[Name];
			}
			if ($cate_row[Type] == "do" || $cate_row[Type] == NULL) {

				if ($cate_row[LinkType] == "1") {
?>
	<tr>
		<td nowrap="nowrap" class="alt2"><a class="smallfont" href="<?=$cate_row[Link]?>" target="<?=$cate_row[Target]?>"><?=$cate_name?></a></td>
	</tr>
<?
				} else {
?>
	<tr>
		<td nowrap="nowrap" class="alt2"><a class="smallfont" href="<?=$config[FileName]?>?<?=$cate_row[Type]?>=<?=$cate_row[FileName]?>"><?=$cate_name?></a></td>
	</tr>
<?
				}
			} elseif (eregi("$cate_row[FileName]","$auth[Permissions]") || $auth[AdminType] == "1") {
?>
	<tr>
		<td nowrap="nowrap" class="alt2"><a class="smallfont" href="<?=$config[FileName]?>?<?=$cate_row[Type]?>=<?=$cate_row[FileName]?>"><?=$cate_name?></a></td>
	</tr>
<?
			}
		}
	}
}
}

?>