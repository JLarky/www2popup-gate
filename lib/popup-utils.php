<?php
/**
* Send popup by popupnicheg and return stout and stderr
*
* @param string from sender name
* @param string to reciever(s) name(s)
* @param string msg message text
*
* @return string
*/

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