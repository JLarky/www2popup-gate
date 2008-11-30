<?php
/**
* Send popup
*
* @param string from sender name
* @param string to reciever(s) name(s) with local aliases
* @param string msg message text
*
* @return string
*/

function send_popup($popup_from, $popup_to, $msg){
if (!is_array($popup_to)) {
$popup_to=str_replace('*', '14 15 16 ALTNET MSHOME NT WORKGROUP', $popup_to);
$popup_to=explode(" ", $popup_to);
}

mysql_select_db($INFO['base_name']);
mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());
$data=mysql_query("select LOWER(`name`) from `alias` group by `name`;");
$localnames=Array();
while ($row = mysql_fetch_row($data)) {
	$localnames[]=$row[0];
}

$emulate_to=Array();
foreach ( $popup_to as $key => $comp) {
	if (in_array($comp, $localnames) ) {
		unset($popup_to[$key]); $emulate_to[]=$comp;
	}
}
$epopups=send_epopup($popup_from, $emulate_to, $msg);

//		$popup_to=join(" ", $popup_to);

$content="";
$status	= Array();
$sended['log']='';
foreach ($popup_to as $popup_too) {
$res=send_ppopup($popup_from, $popup_too, $msg);
$res=explode("\n", $res);
foreach ($res as $str) {
	$sended['log'] .= $str."\n";
	if (mb_ereg("[^']+'(.+)' was not sent.*", $str, $reg_srt)) 
	$status[]=Array($popup_too, 'error', 1);
	if (mb_ereg(".*([0-9]+)/([0-9]+) were sent.*", $str, $reg_srt) && $reg_srt[1])  
	$status[]=Array($popup_too, $reg_srt[1], $reg_srt[2]);
}
}

$sended['ok']=$sended['all']=$epopups;$sended['error']=Array();
foreach ($status as $stat) {
	if ($stat[1] == 'error' ) $sended['error'][]=$stat[0];
	$sended['ok']=$sended['ok']+$stat[1];
	$sended['all']=$sended['all']+$stat[2];
}
return $sended;
};

/**
* Send popup by popupnicheg and return stout and stderr
*
* @param string from sender name
* @param string to reciever(!) name
* @param string msg message text
*
* @return string
*/

function send_ppopup($from, $to, $msg) {
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
   2 => array("pipe", "w") // stderr is a file to write to
);

$from_ip='10.0.144.1';
if (is_array($from)) {$from=$from[0]; $from_ip=$from[1];}
$cwd = '.';
if (!strlen($to)) {
//var_dump($to);
return 'Error: $to in null';}
$env = array('IF' => 'eth1', 'to' => $to, 'netbios' => $from, 
	'ip' => $from_ip, 'HOME' => '/home/jlarky', 
	'LANG' => 'ru_RU.UTF-8');

if (isset($_REQUEST['joke'])) { $modif = "figlet -C /usr/share/figlet/utf8.flc -f banner | head -n 63 | tr \"#\\ \" \"\\\$_\" | "; } else { $modif="";};
$process = proc_open($modif.'popupnicheg -H 0:e0:29:2e:82:88 -I $ip --interface "$IF" --netbios "$netbios" "$to"', $descriptorspec, $pipes, $cwd, $env);

if (is_resource($process)) {
    fwrite($pipes[0], $msg);
    fclose($pipes[0]);
    $output=stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $output.=stream_get_contents($pipes[2]);
    fclose($pipes[2]);
    $return_value = proc_close($process);
//var_dump($output);
return $output;
}
}


/**
* Send popup by creating sql record emulating message recieving.
*
* @param string from sender name
* @param array to reciever(s) name(s)
* @param string msg message text
*
* @return int
*/
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
