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
			"CONTENT"		=> "<tr><td>No items to display</td></tr>",
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
	$tpl->parse("MENU", 	array("menu"));

############
# NAVIGATE
  if (isset($_REQUEST['act'])) { $act = $_REQUEST['act'];} else {$act = "gate";};
						  $tpl->assign( array( "ACT" => $act));

  if (isset($_GET['c'])) { $c = intval($_GET['c']);} else {$c = 0;};
						  $tpl->assign( array( "C" => $c));
  if ($c <= $mpopups)	{ $tpl->assign( array( "CN" => "0")); }
				else	{ $tpl->assign( array( "CN" => $c-$mpopups)); };
						  $tpl->assign( array( "CP" => $c+$mpopups));
  if (isset($_GET['u'])) $_REQUEST['u']=$_GET['u'];
  if (isset($_REQUEST['u'])) { $u = ( $_REQUEST['u']==0 ? 0 : max(10,intval($_REQUEST['u'])));} else {$u = 0;};
						  $tpl->assign( array( "U" => $u));
						  $tpl->assign( array( "MPOPUPS" => $mpopups));
						  setcookie("u", $u, 0, "/gate");

  if (!$u == 0 and ($act=="gate" or $act=="private")) {
   						  $tpl->assign( array( "META" => $tpl->get_assigned("META").
						  "<meta http-equiv=\"Refresh\" content=\"{U}; url={ACT}-{C}&amp;u={U}\" />" ));

				};

  if ($act=="gate" and isset($_COOKIE['has_js']) and $c == 0) {
 						  $tpl->assign( array( "SCRIPTS" => '<script type="text/javascript" src="/js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="/js/jquery.timer.js"></script>
<script type="text/javascript" src="updater.js"></script>'));
  }
	$tpl->parse("NAVIGATE", array("navigate"));

############
# MAIN
	include "inc/main.php";

############
# STBAR
 $mtime = microtime();$mtime = explode(" ",$mtime);$mtime= 
 $mtime[1]+$mtime[0];$tend=$mtime;$totaltime=($tend-$tstart);
 $tpl->assign( array( "TIME" => sprintf("%.3f",$totaltime)));
 $tpl->parse("STBAR", 	array("stbar"));

################
# Making content
################
 $tpl->parse("start", 	array("start"));
 $tpl->FastPrint();
?>