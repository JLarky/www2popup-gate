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
include("inc/fasttemplates.php");
include("lib/theme.php");
include("lib/popup-utils.php");

// include("inc/settings.php");
// include("inc/stat.php");

########
# Var
#########
$mpopups=20;
$vars=Array();


# Выбор Темы оформления
	$tpl = new FastTemplate("design/".$INFO["site_design"]);

	$tpl->define(array("start"		=> "index.tpl"));
	$tpl->define(array("menu"		=> "menu.tpl"));
	$tpl->define(array("navigate"		=> "navigate.tpl"));
	$tpl->define(array("snavigate"		=> "snavigate.tpl"));
	$tpl->define(array("popup"		=> "popup.tpl"));
	$tpl->define(array("login"		=> "login.tpl"));
	$tpl->define(array("settings"		=> "settings.tpl"));
	$tpl->define(array("stbar"		=> "stbar.tpl"));
	$tpl->define(array("search"		=> "search.tpl"));
	$tpl->define(array("send"		=> "send.tpl"));
	$tpl->define(array("sended"		=> "sended.tpl"));
	$tpl->define(array("main"		=> "main.tpl"));

	$tpl->assign(
		array( 
//			"CONTENT"		=> "<tr><td>No items to display</td></tr>",
			"LOGOUT1"		=> "<!--",
			"LOGOUT2"		=> "-->",
			"P_F"			=> $USER['user_name'],
			"P_T"			=> "*",
			"MESSAGE"		=> "Здесь могла быть ваша реклама",
			"TITLE"			=> $USER['user_name']."@jlarky.gate",
			"META"			=> "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n",
			"SCRIPTS"		=> ''
			 ) 
				);
	$vars['content']="<tr><td>No items to display</td></tr>";
	$vars['title']=$USER['user_name']."@jlarky.gate";
	$vars['head']=' ';

 if ($USER['user_perm']>0) {
   $tpl->assign(array( 	"LOGOUT1"		=> "",
			"LOGOUT2"		=> "",
			"MESSAGE"		=> "") );
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
	if (!$vars['main']) 
		$vars['main']	= $tpl->MAIN;

############
# STBAR
 $mtime = microtime();$mtime = explode(" ",$mtime);$mtime= 
 $mtime[1]+$mtime[0];$tend=$mtime;$totaltime=($tend-$tstart);
 $tpl->assign( array( "TIME" => sprintf("%.3f",$totaltime)));
 $tpl->parse("STBAR", 	array("stbar"));
	$vars['stbar']	= $tpl->STBAR;

################
# Making content
################
 print theme('index', $vars);
?>
