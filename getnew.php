<?php 
########
# Uses
#########
include("inc/config.php");
mysql_connect($INFO['base_host'],$INFO['base_user'],$INFO['base_pass']);
mysql_query("SET NAMES 'utf8'");
mysql_select_db($INFO['base_name']);
include("inc/cookie.php");
include("inc/header.php");
include("lib/theme.php");

{
	$c=intval($_REQUEST['c']);
	
	############
	# SELECT FROM DB
	mysql_select_db($INFO['base_name']);
	mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());
	$query="SELECT * FROM `".$INFO['base_tabl']. "` WHERE  (`dst_mac`='ff:ff:ff:ff:ff:ff') and 
(`".$INFO['base_tabl']."`.id>'".intval($c)."') order by id limit 1";
	if (isset($query)) {
$nn=0;
include "inc/popups.php";

while ($nn <2) {
	$e = mysql_query($query) or die("Invalid query: " . mysql_error());
	$output=popup_parse($e, false);
//	var_dump($output);
break;
ob_flush();
}
sleep(1);
$nn++;
}
	};
# BROWSE END

if ($output) {
print ($output);
} else {
print " ";
}
die();

?>
