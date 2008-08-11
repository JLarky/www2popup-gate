<?php
function settings_page() {
	global $USER;
	$vars=Array();

	if (isset($_REQUEST['settings'])) {
	$USER['set_header']=0;
	if ($_REQUEST['head']) {
	$USER['set_header']=1;
	};
	$USER['set_outgoing']=0;
	if ($_REQUEST['set_outgoing']) {
	$USER['set_outgoing']=1;
	};
	mysql_query("UPDATE `".$INFO['settings_tabl']."` SET `header`='".$USER['set_header']."' WHERE `id`='".$USER['user_id']."'");
	mysql_query("UPDATE `".$INFO['settings_tabl']."` SET `outgoing`='".$USER['set_outgoing']."' WHERE `id`='".$USER['user_id']."'");
	};
	
	$checked=array("","checked"); # $checked[0]=""; $checked[1]="checked";
	
	$vars["set_header"]	= $checked[$USER['set_header']];
	$vars["set_outgoing"]	= $checked[$USER['set_outgoing']];
	
	return theme("settings", $vars);
}
?>