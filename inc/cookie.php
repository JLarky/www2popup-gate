<?php
/* initial */
 $user_id=0;
 $user_name=$INFO['guest_name'];
 $user_passwd="";
 $user_perm=0;

/* if SESSION */	session_start();
if (isset($_SESSION['user_name']) and isset($_SESSION['user_passwd'])) {
				$user_name	=$_SESSION['user_name'];
				$user_id	=$_SESSION['user_id'];
				$user_passwd	=$_SESSION['user_passwd'];
};

/* if COOKIES */	$d = time()+3600*31*6; // cokies expiration date: 6 month
if (isset($_COOKIE['user_name']) and isset($_COOKIE['user_passwd'])){ // authorize from cookies
				$user_name	=$_COOKIE['user_name'];
				$user_id	=$_COOKIE['user_id'];
				$user_passwd	=$_COOKIE['user_passwd'];
};

/* if AUTH   */

if (isset($_REQUEST['auth'])) {											// authorize by form
 $user_id=0;
 $user_passwd="";
 $user_perm=0;
 $s = @mysql_fetch_row(mysql_query("SELECT * FROM ".$INFO['login_tabl']." WHERE `login`='".$_REQUEST['login']."' and `pass`='".$_REQUEST['pass']."'"));
if (isset($s[0])) {
$user_id=$s[0];
$user_passwd=$s[2];
};
};

if (isset($_REQUEST['P_F'])){
$user_name=$_REQUEST['P_F'];
};


$s = @mysql_fetch_row(mysql_query("SELECT * FROM ".$INFO['login_tabl']." WHERE `id`='".$user_id."' and `pass`='".$user_passwd."'"));
if (isset($s[0])) {
$user_id=$s[0];
$user_name=$s[3];
// $user_passwd=$s[2];
$user_perm=1;
};


if (isset($_REQUEST['act']) && ($_REQUEST['act']=="logout"))
{
$user_id=0;
$user_passwd="";
$user_perm=0;
};

// save SESSION
		$_SESSION['user_id']=$user_id;
		$_SESSION['user_passwd']=$user_passwd;
		$_SESSION['user_name']=$user_name;

// save COOKIES
		setcookie("user_id",	$user_id, $d, $INFO['cookie_path']);
		setcookie("user_passwd",$user_passwd, $d, $INFO['cookie_path']);
		setcookie("user_name",	$user_name, $d, $INFO['cookie_path']);

# массив в котором хранится информация о пользователе
$USER['user_id']=$user_id;
$USER['user_name']=$user_name;
$USER['user_passwd']=$user_passwd;
$USER['user_perm']=$user_perm;

# load other userspecific settings

# add header to sended message
$s = @mysql_fetch_row(mysql_query("SELECT `header`, `outgoing` FROM ".$INFO['settings_tabl']." WHERE `id`='".$user_id."'"));
if (!isset($s[0])) { $s[0]=1;}; $user_header=$s[0];
$USER['set_header']=$s[0];

if (!isset($s[1])) { $s[1]=1;}; $USER['set_outgoing']=$s[1];


?>