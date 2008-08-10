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
include("inc/fasttemplates.php");
include("lib/theme.php");
// include("inc/settings.php");
// include("inc/stat.php");

########
# Var
#########
$mpopups=20;

# Выбор Темы оформления
	$tpl = new FastTemplate("design/".$INFO["site_design"]);
	$tpl->define(array("popup"		=> "popup.tpl"));

	$tpl->assign(
		array( 
			 ) 
				);

############
# MAIN
# BROWSE BEGING
{
	$c=intval($_REQUEST['c']);
	
	############
	# SELECT FROM DB
	mysql_select_db($INFO['base_name']);
	mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());
	$query="SELECT * FROM `".$INFO['base_tabl']. "` WHERE  (`dst_mac`='ff:ff:ff:ff:ff:ff') and (`".$INFO['base_tabl']."`.id>'".intval($c)."') order by id limit 1";
	if (isset($query)) {
$nn=0;
while ($nn <2) {
	$e = mysql_query($query) or die("Invalid query: " . mysql_error());
		include "inc/popups.php";
if ($tpl->POPUPS) {
break;
ob_flush();
}
sleep(1);
$nn++;
}
	};
}; # BROWSE END

if ($tpl->POPUPS) {
print ($tpl->POPUPS);
} else {
print " ";
}
die();

?>