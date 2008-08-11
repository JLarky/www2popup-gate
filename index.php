<?php 
ob_start();

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
include("lib/popup-utils.php");

########
# Var
#########
$mpopups=20;
$vars=Array();

	$vars['content']="<tr><td>No items to display</td></tr>";
	$vars['title']=$USER['user_name']."@jlarky.gate";
	$vars['message']= "Здесь могла быть ваша реклама";
	$vars['p_f']	= $USER['user_name'];
	$vars['p_t']	= "*";
	$vars['head']=' ';

 if ($USER['user_perm']>0) {
	$vars['message']= "Здесь могла быть ваша реклама";
};
################
# Making content
################

############
# MENU
	$vars['menu']	= theme('menu', $vars);

############
# NAVIGATE
	if (isset($INFO['act'][$_REQUEST['act']]))
		     $jl_act=$_REQUEST['act'];
		else $jl_act=$INFO['default_act'];

	$act		= $jl_act;
	$c		= max(0, intval($_REQUEST['c']));
	$vars['act']	= $act;
	$vars['c']	= $c;
	$vars['cn']	= max(0, $c-$mpopups);
	$vars['cp']	= max(0, $c+$mpopups);

  if (isset($_GET['u'])) $_REQUEST['u']=$_GET['u'];
  if (isset($_REQUEST['u'])) { $u = ( $_REQUEST['u']==0 ? 0 : max(10,intval($_REQUEST['u'])));} else {$u = 0;};
		  setcookie("u", $u, 0, "/gate");

	$vars['u']	= $u;
	$vars['mpopups']= $mpopups;

  if (!$u == 0 and ($act=="gate" or $act=="private")) {
	$vars['head'] .= "<meta http-equiv=\"Refresh\" content=\"$u; url=$act-$c&amp;u=$u\" />";
	};
  if ($act=="gate" and isset($_COOKIE['has_js']) and $c == 0) {
	$vars['head'] .= '
<script type="text/javascript" src="/js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="/js/jquery.timer.js"></script>
<script type="text/javascript" src="updater.js"></script>';
  }
	$vars['navigate'] = ($act=="gate" || $act=="private" || $act=="browse") ? theme('navigate', $vars) : '';


############
# MAIN

	include "inc/main.php";

############
# STBAR
 $mtime = microtime();$mtime = explode(" ",$mtime);$mtime= 
 $mtime[1]+$mtime[0];$tend=$mtime;$totaltime=($tend-$tstart);
 	$vars["time"] = sprintf("%.3f",$totaltime);
 	$vars['stbar']	= theme('stbar', $vars);

################
# Making content
################
 print theme('index', $vars);
?>
