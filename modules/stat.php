<?php
function stat_page() {
	global $INFO;

	global $user_id;

	$cache=$vars=Array();

	// получаем последнюю запись, если в кеше то же самое, то отдаем кеш.

	$last_msg = mysql_fetch_array(mysql_query("SELECT * FROM `".$INFO['base_tabl']."` ORDER BY `id` DESC LIMIT 1"));
	echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
		$cache['last_id']=$last_msg['id'];
		$cache['last_popup']=$last_msg['time'];

	list($fromcache) = mysql_fetch_array(mysql_query("SELECT `var` FROM `cache` WHERE `name`='stat_cache'"));
	echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';

	// проверяем
	$fromcache=unserialize($fromcache);
	if ($cache['last_id']==$fromcache['last_id'] and 0) {
		$vars=$fromcache['vars'];
	} else {
		//echo 123;
	
	//	$cache['']=$last_msg[''];
	
		if ($user_id==1) {
		//var_dump($fromcache);
		//return '';
		}

		$tmp = mysql_fetch_array(mysql_query("SELECT * FROM `".$INFO['base_tabl']."` ORDER BY `id` ASC LIMIT 1"));
		echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
		$vars['first_popup']=$tmp['time'];

	
	
		list($popups_total) = mysql_fetch_array(mysql_query("SELECT count(*) as 'count' FROM `".$INFO['base_tabl']."`"));
		echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
		$vars['popups_total'] = $popups_total;
	
		list($chars_total) = mysql_fetch_array(mysql_query("SELECT SUM(LENGTH(`msg`)) as 'chars' FROM `".$INFO['base_tabl']."`"));
		echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
		$vars['chars_total'] = $chars_total;
	
	
// fluders
	if (true) {
		$q="select src_mls as 'nick', count(*) as 'popups', sum(char_length(msg)) as 'chars', max(time) as 'last' from popup.popups where LOWER(`dst_mac`)='ff:ff:ff:ff:ff:ff' group by 1 order by 2 desc limit 21;";
		$e = mysql_query($q);
		echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
		$output='';$i=1;
		while ($row = mysql_fetch_array($e)) {
			//var_dump($row);
			$row['num']=$i++;
			$output .= theme('stat_row', $row, $vars);
		}

	} else {		
		$q="select count(*) as 'popups', SUM(LENGTH(`msg`)) as 'chars', LOWER(src_mls) as 'nick' from `".$INFO['base_tabl']."` WHERE LOWER(dst_mac)='ff:ff:ff:ff:ff:ff' GROUP BY src_mls ORDER BY `popups` DESC limit 21";
		$e = mysql_query($q);
		echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
	
		$output='';$i=1;
		while ($row = mysql_fetch_array($e)) {
			$row['num']=$i++;
			list($last) = mysql_fetch_array(mysql_query("SELECT `time` FROM `".$INFO['base_tabl']."` WHERE LOWER(dst_mac)='ff:ff:ff:ff:ff:ff' and LOWER(`src_mls`)=LOWER('".$row['nick']."') ORDER BY `id` DESC"));
			echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
			$row['last']= $last;
			$output .= theme('stat_row', $row, $vars);
		}
	}
		$vars['stat'] = $output;
	
		$cache['popups_total']=$vars['popups_total'];
	
		$cache['vars']=$vars;
	
		$var=mysql_real_escape_string(serialize($cache));
	
		mysql_query("INSERT INTO `cache` SET `name`='stat_cache', `var`='$var' ON DUPLICATE KEY UPDATE `var`='$var'");
		//var_dump(mysql_affected_rows());
		echo mysql_error() ? mysql_error().' - '.__FILE__.':'.__LINE__ : '';
	
	}

return theme('stat', $vars, $cache);

$profiling=false;
if ($profiling) {
 $q="SET profiling = 1;";
 $e = @mysql_query($q);
};


############
# PROCESSING DATA

// создаём временную таблицу из базы всех общих попапов
 $q="CREATE TEMPORARY TABLE `tmp_".$INFO['base_tabl']."` TYPE=HEAP SELECT `id`, `src_ntb`, `time`, LENGTH(`msg`) AS 'len' FROM ".$INFO['base_tabl']." WHERE `".$INFO['base_tabl']."`.`dst_mac`='ff:ff:ff:ff:ff:ff'";
 $e = mysql_query($q);
//echo $q;
$e = mysql_query("SELECT COUNT(*) FROM `tmp_".$INFO['base_tabl']."`");
$s = mysql_fetch_row($e);
	$tpl->assign( array( "ALL_POPUP" 	=> $s[0]));
// echo $s[0];
$q="SELECT COUNT(*) FROM `tmp_".$INFO['base_tabl']."` WHERE CURDATE()=DATE(`time`)";
$e = @mysql_query($q);
$s = @mysql_fetch_row($e);
	$tpl->assign( array( "TODAY_POPUP" 	=> $s[0]));

$q="SELECT COUNT(*) FROM `tmp_".$INFO['base_tabl']."` WHERE CURDATE()=DATE(`time`)+1";
$e = @mysql_query($q);
$s = @mysql_fetch_row($e);
	$tpl->assign( array( "YESTERDAY_POPUP" 	=> $s[0]));

$q="SELECT `time` FROM `tmp_".$INFO['base_tabl']."` LIMIT 1";
$e = @mysql_query($q);
$s = @mysql_fetch_row($e);
	$tpl->assign( array( "FIRS_POPUP" 	=> $s[0]));

$q="SELECT `time` FROM `tmp_".$INFO['base_tabl']."` ORDER BY `id` DESC  LIMIT 1";
$e = @mysql_query($q);
$s = @mysql_fetch_row($e);
	$tpl->assign( array( "LATER_POPUP" 	=> $s[0]));

$e = @mysql_query("SELECT SUM(`len`) FROM `tmp_".$INFO['base_tabl']."`");
$s = @mysql_fetch_row($e);
	$tpl->assign( array( "ALL_CHARSET" 	=> $s[0]));

$topflud=0;
$e = @mysql_query("SELECT UPPER(`src_ntb`), COUNT(*) AS `kol`, MAX(`time`), SUM(`len`) FROM `tmp_".$INFO['base_tabl']."` GROUP BY `src_ntb` ORDER BY `kol` DESC LIMIT 21");
  while($s = @mysql_fetch_row($e)){
$topflud=$topflud+1;
	$tpl->assign( array( "FLUDERSTOP" 	=> $topflud));
	$tpl->assign( array( "SRC_NTB" 	=> $s[0]));
	$tpl->assign( array( "COUNT_OF_POPUPS" 	=> $s[1]));
 	$tpl->assign( array( "PERCENT_OF_POPUPS" => sprintf("%.2f",($s[1]/$tpl->get_assigned("ALL_POPUP"))*100)));

	$tpl->assign( array( "LAST_POPUP" 	=> $s[2]));
	$tpl->assign( array( "COUNT_OF_C" 	=> $s[3]));
 	$tpl->assign( array( "PERCENT_OF_C" => sprintf("%.2f",($s[3]/$tpl->get_assigned("ALL_CHARSET"))*100)));

	$tpl->parse("FLUDER", "fluders");
	@$tpl->parse("FLUDERS", ".fluders");
	$tpl->parse("STAT", "stat");
  };
if ($profiling) {
 $q="SHOW PROFILES";
  $e = @mysql_query($q);
  while($s = @mysql_fetch_row($e)){
 echo "<!--";
 print_r($s);
 echo "-->";
 };
};
// 	$tpl->assign( array( "LATER_POPUP" 	=> $s[0]));
}
?>
