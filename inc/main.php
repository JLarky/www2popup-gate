<?php
if (isset($INFO['act'][$_REQUEST['act']]))
		{ $jl_act=$_REQUEST['act'];} else {$jl_act=$INFO['default_act'] ;};
# SEND BEGIN
if ($jl_act=="send") {
	$tpl->assign(array( "NAVIGATE"	=> ""));
if (isset($_REQUEST['P_F']) and isset($_REQUEST['P_T']) and isset($_REQUEST['P_M'])) {
$ip=getenv("REMOTE_ADDR");

// print_r($_REQUEST);
if (!isset($_REQUEST['sendlater'])) {


 if (isset($USER['set_header']) and $USER['set_header'])
	$msg = "From $ip # http://jlarky.dorms.spbu.ru/gate\n";
  else
  	$msg="";
  	
  $msg .= stripslashes(str_replace("\r", "", $_POST['P_M'] ));

  mysql_select_db($INFO['base_name']);
  mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());
  $data=mysql_query("select LOWER(`name`) from `alias` group by `name`;");
  $localnames=Array();
  while ($row = mysql_fetch_row($data)) {
  	$localnames[]=$row[0];
  }

 $popup_to=explode(" ", $_REQUEST['P_T']);
 $emulate_to=Array();
 foreach ( $popup_to as $key => $comp) {
	if (in_array($comp, $localnames) ) {
		unset($popup_to[$key]); $emulate_to[]=$comp;
	}
 }
 $epopups=send_epopup($_REQUEST['P_F'], $emulate_to, $msg);
 
 $popup_to=join(" ", $popup_to);
 foreach ($localnames as $name) //"(jlarky)([$|\ ])", "\\1%10.0.144.1\\2"
  $popup_to=mb_eregi_replace("($name)($|\ )", "\\1%127.0.0.1%00:e0:29:2e:82:88\\2", $popup_to);


$content="";
$res=send_popup($_REQUEST['P_F'], $popup_to, $msg);
$errs=Array();
$res=explode("\n", $res);
foreach ($res as $str) {
	if (mb_ereg("[^']+'(.+)' was not sent.*", $str, $reg_srt))
	$content .= "Ошибка отправки на <font color=\"red\">{$reg_srt[1]}</font><br />\n";
	elseif (mb_ereg(".*([0-9]+)/([0-9]+) were sent.*", $str, $reg_srt)) 
	$content .= ($reg_srt[1]+$epopups)." из ".($reg_srt[2]+$epopups)." было отправлено.<br />\n";
}
//$content .="<pre>".print_r($errs, 1)."</pre>";

} else {
if ($USER['user_perm'] >= 1) {
include "inc/sendlater.php";
if ( (isset($_REQUEST['P_T'])) and ($_REQUEST['P_T']!="") ) {
$content="Сообщение добавленно в очередь";
$tmp= new PopupSender($_REQUEST['P_T'], $_REQUEST['P_F'], "From " .$ip. " # http://jlarky.dorms.spbu.ru/gate\n".$_REQUEST['P_M']);
} else 
{
$content="вазник эрор";
};
// $content="Счас всё будет";
} else {
$content="Извините Вам эта функция не доступна пользуйтесь кнопкой \"отправить\"";
} 
}
	$tpl->assign(array( "SENDLOG"	=> "<p align=center>".$content."<p>"));
	$_POST['P_M'] = stripslashes($_POST['P_M']);
 	$_POST['P_M'] = htmlspecialchars($_POST['P_M']);
	$_POST['P_M'] = str_replace ("{", "&#123;", $_POST['P_M']);
	$_POST['P_M'] = str_replace ("}", "&#125;", $_POST['P_M']);

	$tpl->assign(array( "MESSAGE"	=> $_POST['P_M']));
	$tpl->assign(array( "P_T"	=> htmlspecialchars($_REQUEST['P_T'])));
	$tpl->assign(array( "P_F"	=> htmlspecialchars($_REQUEST['P_F'])));
	$tpl->parse("CONTENT", array("send"));
} else {
	$tpl->assign(array( "SENDLOG"	=> ""));

if (@$_REQUEST['id'] != "") {

	if ($USER['set_outgoing']) { # показывать ли исходящие сообщения
	$outgoing="UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`src_ntb`)";
	} else {
	$outgoing=0;
	};


############
# SELECT FROM DB
  $query="SELECT * FROM `".$INFO['base_tabl']. "` WHERE (`id`=".intval($_REQUEST['id']).") and ((`dst_mac`!='ff:ff:ff:ff:ff:ff' and (SELECT count(*) FROM `".$INFO['alias_tabl']."` WHERE `uid`=1 and (UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`dst_ntb`) or $outgoing))) or `dst_mac`='ff:ff:ff:ff:ff:ff') LIMIT 1";
  $s = @mysql_fetch_row(mysql_query($query));
  $s[8] = ">" . $s[8];
  $s[8] = str_replace("\r", "",$s[8]);
  $s[8] = str_replace("\n", "\n>",$s[8]);
  $s[8] = $s[8]."\r\n>\r\n";
  $s[8] = htmlspecialchars($s[8	]);
	$s[8] = str_replace ("{", "&#123;", $s[8]);
	$s[8] = str_replace ("}", "&#125;", $s[8]);
	$tpl->assign(array( "MESSAGE"	=> $s[8]));
	$tpl->assign(array( "P_T"	=> $s[3]));
	$tpl->assign(array( "P_F"	=> $user_name));

};

$tpl->parse("CONTENT", array("send"));
};
}; # SEND END

# GATE and PRIVATE BEGING
if ($jl_act=="gate" or $jl_act=="private" or $jl_act=="search2")
{
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
	$tpl->assign(array( "CONTENT"	=> "{POPUPS}"));
};
}; # GATE and PRIVATE END

# ABOUT BEGIN
if ($jl_act=="about") {
	$tpl->assign(array( "NAVIGATE"	=> ""));
$text="";
$s = @mysql_fetch_row(mysql_query("SELECT * FROM ".$INFO['texts_tabl']." WHERE `id`='1'"));
if (isset($s[1])) {
$text=$s[1];
}
	$tpl->assign(array( "CONTENT"	=> $text));
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
	$tpl->assign( array( "CP" => $cp));
	$tpl->assign( array( "CN" => $cn));
	$tpl->assign( array( "C" => $c));
	$tpl->assign( array( "MPOPUPS" => ""));
		$tpl->parse("NAVIGATE", array("navigate"));
// 	$c=396711;
	$query="SELECT * FROM `".$INFO['base_tabl']. "` WHERE  `".$INFO['base_tabl']."`.id='".intval($c)."' limit 1";
	if (isset($query)) {
	$e = mysql_query($query) or die("Invalid query: " . mysql_error());
	
		include "popups.php";
		$tpl->assign(array( "CONTENT"	=> "{POPUPS}"));
	};
}; # BROWSE END




# STAT BEGING
if ($jl_act=="stat") {
	include "stat.php";
	$tpl->assign(array( "NAVIGATE"	=> ""));
	$tpl->assign(array( "CONTENT"	=> "{STAT}"));
}; # STAT END

# SEARCH BEGING
if ($jl_act=="search") {
//	include "stat.php";
	$tpl->assign(array( "NAVIGATE"	=> ""));
	$tpl->assign(array( "CONTENT"	=> "{STAT}"));
	$tpl->parse("CONTENT", 	array("search"));
}; # SEARCH END


# SETTINGS BEGING
if ($jl_act=="settings" and $user_perm>0) {
	include "settings.php";
	$tpl->assign(array( "NAVIGATE"	=> ""));
	$tpl->assign(array( "CONTENT"	=> "{SETTINGS}"));
}; # SETTINGS END


if ($user_perm==1) {$tpl->assign(array( "LOGOUT1"=>"","LOGOUT2"=>""));};
	$tpl->parse("MAIN", 	array("main"));
?>
