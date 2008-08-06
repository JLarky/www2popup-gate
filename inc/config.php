<?php
$INFO['site_design']			=	'default';
$INFO['admin_mail']			=	'jlarky@gmail.com';
$INFO['base_host']			=	'localhost';
$INFO['base_user']			=	'notreal';
$INFO['base_pass']			=	"real i'll get from conf-file";
if (file("/etc/www2popup/config.php"))
	include "/etc/www2popup/config.php";
$INFO['base_name']			=	'popup';
$INFO['base_tabl']			=	'popups';
$INFO['alias_tabl']			=	'alias';
$INFO['login_tabl']			=	'users';
$INFO['texts_tabl']			=	'text';
$INFO['settings_tabl']			=	'settings';
$INFO['act']				=	Array	("about"	=> "content/about.html"
							,"bla"		=> "content/bla.html"
							,"gate"		=> ""
							,"private"	=> ""
							,"browse"	=> ""
							,"send"		=> ""
							,"stat"		=> ""
							,"settings"	=> ""
							,"search"	=> ""
							,"search2"	=> ""
							);
$INFO['default_act']			=	'gate';
$INFO['guest_name']			=	'Anonymous';
?>
