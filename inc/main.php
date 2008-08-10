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

function send_popup($from, $to, $msg) {
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("pipe", "w") // stderr is a file to write to
);

$cwd = '.';
$to=mb_ereg_replace("\*", "14 15 16 ALTNET MSHOME NT WORKGROUP",$to);
$from=mb_ereg_replace("\*", "'*'",$from);
$env = array('IF' => 'eth1', 'to' => $to, 'netbios' => $from, 'HOME' => '/home/jlarky', 'LANG' => 'ru_RU.UTF-8');

$process = proc_open('popupnicheg -H 0:e0:29:2e:82:88 -I 10.0.144.1 --interface $IF --netbios $netbios $to', $descriptorspec, $pipes, $cwd, $env);

if (is_resource($process)) {
    fwrite($pipes[0], $msg);
    fclose($pipes[0]);
    $output=stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $output.=stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $return_value = proc_close($process);
return $output;
}
}
function send_epopup($from, $to=Array(), $msg) {
	mysql_select_db("popup");
	mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());
	$ip="10.0.144.1";$mac="00:e0:29:2e:82:88";$i=0;
   foreach ($to as $comp) {
	$comp=mysql_real_escape_string($comp);$msg=mysql_real_escape_string($msg);
	mysql_query("INSERT INTO `popups` (`src_mac`, `src_ip`, `src_ntb`, `src_mls`, `dst_mac`, `dst_ntb`, `dst_mls`, `msg`)
	VALUES ('$mac', '$ip', '$from', '$from', '$mac', '$comp', '$comp', '$msg') ;");
	echo mysql_error();$i++;
   }
	return $i;
}

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
	$_