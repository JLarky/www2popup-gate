<?php
# SEND BEGIN
if ($jl_act=="send") {
	include "modules/send.php";
	$vars['content']=send_page();
};# SEND END

# GATE and PRIVATE BEGING
if ($jl_act=="gate" or $jl_act=="private" or $jl_act=="search2") {
############
# SELECT FROM DB
  mysql_select_db($INFO['base_name']);
  mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());

if ($jl_act=='private') {

if ($USER['user_perm']==0) {
	$tpl->assign(array( "CONTENT"	=> "{LOGIN}"));
	$tpl->parse("LOGIN", array("login"));
;}
else {
if ($USER['set_outgoing']) { # показывать ли исходящие сообщения
//$outgoing="UPPER(`".$INFO['base_tabl']. "`.src_ntb)=UPPER(`".$INFO['alias_tabl']."`.name) and UPPER(`".$INFO['alias_tabl']."`.uid)='".$user_id."'";
$outgoing="UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`src_ntb`)";
} else {
$outgoing=0;
};
//$query="SELECT * FROM `".$INFO['base_tabl']. "`,`".$INFO['alias_tabl']."` WHERE (UPPER(`".$INFO['base_tabl']. "`.dst_ntb)=UPPER(`".$INFO['alias_tabl']."`.name) and UPPER(`".$INFO['alias_tabl']."`.uid)='".$user_id."') or ($outgoing and `dst_mac`!='ff:ff:ff:ff:ff:ff') ORDER BY `".$INFO['base_tabl']. "`.id DESC LIMIT " . $c . ", ".$mpopups;

$query="SELECT * FROM `".$INFO['base_tabl']. "` where `dst_mac`!='ff:ff:ff:ff:ff:ff' and (SELECT count(*) FROM `".$INFO['alias_tabl']."` where `uid`='$user_id' and (UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`popups`.`dst_ntb`) or $outgoing) ) order by `id` desc limit " . $c . ", ".$mpopups;
};
} else {$query="SELECT * FROM ".$INFO['base_tabl']. " WHERE dst_mac='ff:ff:ff:ff:ff:ff' ORDER BY id DESC LIMIT " . $c . ", $mpopups";};

if (0 and $USER['user_id'] == 1 and $jl_act=="search2") {
$who=$_REQUEST['who'];
$query="SELECT * FROM ".$INFO['base_tabl']. " WHERE ( ((UPPER(src_ntb)='JLARKY' or UPPER(src_ntb)='PROXY') and UPPER(dst_ntb)=UPPER('$who')) or ((UPPER(dst_ntb)='JLARKY' or UPPER(dst_ntb)='PROXY') and UPPER(src_ntb)=UPPER('$who')) ) and dst_mac!='ff:ff:ff:ff:ff:ff' ORDER BY id DESC LIMIT " . $c . ", $mpopups";
};
  if (isset($query)) {
$e = mysql_query($query) or die("Invalid query: " . mysql_error());

	include "popups.php";
	$vars['content']=popup_parse($e);
};
}; # GATE and PRIVATE END

# ABOUT BEGIN
if ($jl_act=="about") {
	$vars['navigate']='';
$text="";
$s = @mysql_fetch_row(mysql_query("SELECT * FROM ".$INFO['texts_tabl']." WHERE `id`='1'"));
if (isset($s[1])) {
$text=$s[1];
}
	$vars['content']=$text;
}; # ABOUT END



# BROWSE BEGING
if ($jl_act=="browse")
{
	$cp=-1;
	$c=-1;
	$cn=-1;
	
	############
	# SELECT FROM DB
	mysql_select_db($INFO['base_name']);
	mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());

	if ($USER['set_outgoing']) { # показывать ли исходящие сообщения
	$outgoing="UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`src_ntb`)";
	} else {
	$outgoing=0;
	};

if (intval($_REQUEST['c']) < 30) $_REQUEST['c']=30;
	$query="SELECT `id` FROM `".$INFO['base_tabl']. "` WHERE (`id`<=".intval($_REQUEST['c']).") and ((`dst_mac`!='ff:ff:ff:ff:ff:ff' and (SELECT count(*) FROM `".$INFO['alias_tabl']."` WHERE `uid`='{$USER['user_id']}' and (UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`dst_ntb`) or $outgoing))) or `dst_mac`='ff:ff:ff:ff:ff:ff') ORDER BY `id` DESC LIMIT 2";
	$e = mysql_query($query);
	if ($e) {
	$s = @mysql_fetch_row($e);
	$c=intval($s[0]);
// 	echo $c."-";
	$s = @mysql_fetch_row($e);
	$cp=intval($s[0]);
// 	echo $cp;
	};
	$query="SELECT `id` FROM `".$INFO['base_tabl']. "` WHERE (`id`>".intval($_REQUEST['c']).") and ((`dst_mac`!='ff:ff:ff:ff:ff:ff' and (SELECT count(*) FROM `".$INFO['alias_tabl']."` WHERE `uid`='{$USER['user_id']}' and (UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`dst_ntb`) or $outgoing))) or `dst_mac`='ff:ff:ff:ff:ff:ff') ORDER BY `id` ASC LIMIT 1";
	$e = mysql_query($query);
	if ($e) {
	$s = @mysql_fetch_row($e);
	$cn=intval($s[0]);
// 	echo "-".$cn;
	};
	if ($c<=0) {
	$c=0;$cp=0;
	};
	if ($cn<=0) {
	$cn=$c+1;
	}
	
	//echo "$cmax $cn $c $cp";
	$vars['cn']	=  $cn;
	$vars['cp']	=  $cp;
	$vars['c']	=  $c;
	$vars['mpopups']=  $mpopups;
	$vars['navigate']=theme('navigate', $vars);
	$query="SELECT * FROM `".$INFO['base_tabl']. "` WHERE  `".$INFO['base_tabl']."`.id='".intval($c)."' limit 1";
	if (isset($query)) {
	$e = mysql_query($query) or die("Invalid query: " . mysql_error());
	
		include "popups.php";
	$vars['content']= popup_parse($e);

	};
}; # BROWSE END




# STAT BEGING
if ($jl_act=="stat") {
	include "modules/stat.php";
	$vars['content']=stat_page();
}; # STAT END

# SEARCH BEGING
if ($jl_act=="search") {
//	include "stat.php";
	$vars['navigate']='';
	$tpl->assign(array( "CONTENT"	=> "{STAT}"));
	$tpl->parse("CONTENT", 	array("search"));
}; # SEARCH END


# SETTINGS BEGING
if ($jl_act=="settings" and $user_perm>0) {
	include "settings.php";
	$vars['navigate']='';
	$tpl->assign(array( "CONTENT"	=> "{SETTINGS}"));
}; # SETTINGS END


if ($user_perm==1) {$tpl->assign(array( "LOGOUT1"=>"","LOGOUT2"=>""));};
	if (!$tpl->CONTENT) $tpl->assign(array( "CONTENT"	=> $vars['content']));
	//var_dump($vars['content']);

	$tpl->parse("MAIN", 	array("main"));
?>
