<?php
################################
# output:	{STAT}
################################

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

?>
