<?php
function send_page() {
global $user_name, $USER, $INFO;
	$vars=Array();
	if (isset($_REQUEST['p_f']) and isset($_REQUEST['p_t']) and isset($_REQUEST['p_m'])) { // i.e. we are going to send message
		$ip=getenv("REMOTE_ADDR");
	
	if (!isset($_REQUEST['sendlater'])) {
		if (isset($USER['set_header']) and $USER['set_header'])
			$msg = "From $ip # http://jlarky.dorms.spbu.ru/gate\n";
		else
			$msg = "";
			
		$msg .= stripslashes(str_replace("\r", "", $_POST['p_m'] ));
		
		mysql_select_db($INFO['base_name']);
		mysql_query("SET NAMES 'utf8'") or die("Invalid query: " . mysql_error());
		$data=mysql_query("select LOWER(`name`) from `alias` group by `name`;");
		$localnames=Array();
		while ($row = mysql_fetch_row($data)) {
			$localnames[]=$row[0];
		}
		
		$popup_to=explode(" ", $_REQUEST['p_t']);
		$emulate_to=Array();
		foreach ( $popup_to as $key => $comp) {
			if (in_array($comp, $localnames) ) {
				unset($popup_to[$key]); $emulate_to[]=$comp;
			}
		}
		$epopups=send_epopup($_REQUEST['p_f'], $emulate_to, $msg);
		
		$popup_to=join(" ", $popup_to);
		foreach ($localnames as $name) //"(jlarky)([$|\ ])", "\\1%10.0.144.1\\2"
		$popup_to=mb_eregi_replace("($name)($|\ )", "\\1%127.0.0.1%00:e0:29:2e:82:88\\2", $popup_to);
		
		
		$content="";
		$res=send_popup($_REQUEST['p_f'], $popup_to, $msg);
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
		if ($USER['user_perm'] > 0) {
			include "inc/sendlater.php";
			if ( (isset($_REQUEST['p_t'])) and ($_REQUEST['p_t']!="") ) {
			$content="Сообщение добавленно в очередь";
			$tmp= new PopupSender($_REQUEST['p_t'], $_REQUEST['p_f'], "From " .$ip. " # http://jlarky.dorms.spbu.ru/gate\n".$_REQUEST['p_m']);
			} else {
			$content="вазник эрор";
			};
			// $content="Счас всё будет";
		} else {
			$content="Извините, эта функция доступна только зарегистрированным пользователям. Попробуйте кнопку \"отправить\"";
		} 
	}
		$vars['sendlog']	= "<p align=center>".$content."<p>";
		$_POST['p_m']		= stripslashes($_POST['p_m']);
		$_POST['p_m']		= htmlspecialchars($_POST['p_m']);
		//$_POST['p_m'] = str_replace ("{", "&#123;", $_POST['p_m']);
		//$_POST['p_m'] = str_replace ("}", "&#125;", $_POST['p_m']);
	
		$vars["message"]	= $_POST['p_m'];
		$vars["p_t"]		= htmlspecialchars($_REQUEST['p_t']);
		$vars["p_f"]		= htmlspecialchars($_REQUEST['p_f']);
		return theme('send', $vars);
	} else {
		$vars['sendlog']	= "";
	
		if (@$_REQUEST['id'] != "") {
		
			if ($USER['set_outgoing']) { # показывать ли исходящие сообщения
			$outgoing="UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`src_ntb`)";
			} else {
			$outgoing=0;
			};
		
		
			############
			# SELECT FROM DB
			$query="SELECT * FROM `".$INFO['base_tabl']. "` WHERE (`id`=".intval($_REQUEST['id']).") and ((`dst_mac`!='ff:ff:ff:ff:ff:ff' and (SELECT count(*) FROM `".$INFO['alias_tabl']."` WHERE `uid`=1 and (UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`dst_ntb`) or $outgoing))) or `dst_mac`='ff:ff:ff:ff:ff:ff') LIMIT 1";
			$s = @mysql_fetch_row(mysql_query($query));
			$s[8] = ">" . $s[8];
			$s[8] = str_replace("\r", "",$s[8]);
			$s[8] = str_replace("\n", "\n>",$s[8]);
			$s[8] = $s[8]."\r\n>\r\n";
			$s[8] = htmlspecialchars($s[8	]);
			$vars['message']	= $s[8];
			$vars['p_t']		= $s[3];
			$vars['p_f']		= $user_name;
		
		};
	}
	return theme('send', $vars);
};