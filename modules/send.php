<?php
function send_page() {
global $user_name, $USER, $INFO, $vars, $user_id;
	if (isset($_REQUEST['p_f']) and isset($_REQUEST['p_t']) and isset($_REQUEST['p_m'])) { // i.e. we are going to send message
		$ip=getenv("REMOTE_ADDR");
		$_SESSION['user_name']=$_REQUEST['p_f']; // save user name
	
	if (!isset($_REQUEST['sendlater'])) {
		$msg = (isset($USER['set_header']) and $USER['set_header']) ? "From $ip # http://jlarky.dorms.spbu.ru/gate\n" : "";
		$msg .= stripslashes(str_replace("\r", "", $_POST['p_m'] ));
		$popup_to=$_REQUEST['p_t'];
		$popup_from=$_REQUEST['p_f'];
		
		$sended=send_popup($popup_from, $popup_to, $msg);

		$content .= ($sended['ok']+$epopups)." из ".($sended['all']+$epopups)." было отправлено.<br />\n";
		foreach ($sended['error'] as $comp)
			$content .= "Ошибка отправки на <span style=\"color:red\">{$comp}</span><br />\n";
	
	} else {
		if ($USER['user_perm'] > 0) {
			include "inc/sendlater.php";
			if ( (isset($_REQUEST['p_t'])) and ($_REQUEST['p_t']!="") ) {
			$content="Сообщение добавленно в очередь";
			$tmp = sendlater($_REQUEST['p_t'], $_REQUEST['p_f'], "From " .$ip. " # http://jlarky.dorms.spbu.ru/gate\n".$_REQUEST['p_m']);
			} else {
			$content="вазник эрор";
			};
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
			$query="SELECT * FROM `".$INFO['base_tabl']. "` WHERE (`id`=".intval($_REQUEST['id']).") and ((`dst_mac`!='ff:ff:ff:ff:ff:ff' and (SELECT count(*) FROM `".$INFO['alias_tabl']."` WHERE `uid`=".intval($user_id)." and (UPPER(`".$INFO['alias_tabl']."`.`name`)=UPPER(`".$INFO['base_tabl']. "`.`dst_ntb`) or $outgoing))) or `dst_mac`='ff:ff:ff:ff:ff:ff') LIMIT 1";
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
