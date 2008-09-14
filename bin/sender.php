<?php
################
# MySQL connect
	include("../inc/config.php");
	mysql_connect($INFO['base_host'],$INFO['base_user'],$INFO['base_pass']);
	mysql_query("SET NAMES 'utf8'");
	mysql_select_db($INFO['base_name']);
	$error=mysql_error();echo ($error ? $error.__LINE__ : '');
	
################
# Libs
	include "../lib/popup-utils.php";

################
# Get list to send
	$query="SELECT * from `".$INFO['later_tabl']."` WHERE (TIMEDIFF(NOW(), time)>500) OR (`counter` < 15) ORDER BY `counter`, `time` ASC LIMIT 60;";
	$sql_res=mysql_query($query);
	$error=mysql_error();echo ($error ? $error.__LINE__ : '');
	while ($row=mysql_fetch_array($sql_res)) {
		$from=$row['from'];
		$to=$row['to'];
		$msg=$row['msg'];
		$content="";
		$res=send_popup($from, $to, $msg);
		$errs=Array();
		$res=explode("\n", $res);
		$ok=false;
		foreach ($res as $str) {
			if (mb_ereg(".*([0-9]+)/([0-9]+) were sent.*", $str, $reg_srt) && $reg_srt[1]) 
				{ $ok=true;break; }
		}
		        if ($ok) {
                        // Succsess
                        mysql_query("DELETE FROM `".$INFO['later_tabl']."` WHERE `id`='".intval($row['id'])."'");
                        } else {
                        // Error
                        $log=mysql_real_escape_string(join("\n", $res));
                        mysql_query("UPDATE `".$INFO['later_tabl']."` SET `counter`=`counter`+1, `comment`='$log'  WHERE
`id`='".intval($row['id'])."'");
                        }

	}

?>
