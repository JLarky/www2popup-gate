<?php
/**
 * Parse message body and add Google adSense
 *
 * @param string message message body
 * @param string adsense TRUE for include adsense
 *
 * @return string
 */

function popup_messageparse($message, $adsense = false) {
	global $USER;
	$message = htmlspecialchars($message);
	//$s[6] = str_replace ("{", "&#123;", $s[6]);
	//$s[6] = str_replace ("}", "&#125;", $s[6]);
	$message = preg_replace("#(https?|ftps?|ed2k|file|smb|mms|news)://\S+[^\s.,>)\];'\"!?]#", '<a href="\\0" target="_blank">\\0</a>', $message);
	$message = str_replace ("\r", "", $message);
	$message = str_replace ("\n", "<br />", $message);

	//$s[6] = preg_replace("#\\\\\S+[^\s.,>)\];'\"!?]#", '<a href="file://\\0" target="_blank">\\0</a>', $s[6]);
	if (!$USER["user_id"] and $_SERVER["HTTP_HOST"]=="jlarky.dorms.spbu.ru" and $adsense and (!isset($_REQUEST['no_spam']) or (isset($_REQUEST['no_spam']) and $_REQUEST['no_spam']!='1'))) {
	
	$spam='<br />
<div style="margin-left:-1px;"><div style="margin-bottom:-2em;padding-right:10px;text-align:right;"><a href="#" title="Больше не показывать" onclick="javascript:document.cookie=\'no_spam=1; path=/gate\';">[X]</a></div>
<script type="text/javascript"><!--
google_ad_client = "pub-4133977401131421";
/* fake massage */
google_ad_slot = "7280936109";
google_ad_width = 727;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>';
	} else {
	$spam='';
	}

	return $message.$spam;
}
/**
 * Parse popup message
 *
 * @param mysql_resource mysql_resource result of mysql_query
 *
 * @return string
 */
function popup_parse($mysql_resource) {
	static $pcont=0;
	$output='';
	$vars=Array();
	while($s = @mysql_fetch_row($mysql_resource)){
		$pcont++;
		$vars["ID"]	= $s[0];
		$vars["MAC"]	= $s[1];
		$vars["IP"]	= $s[2];
		$vars["COMP"]	= htmlspecialchars($s[3]);
		$vars["NICK"]	= htmlspecialchars($s[4]);
		$vars["DATE"]	= $s[9];
		$vars["TO"]	= htmlspecialchars($s[6]);
		$s[8]=str_replace("http://remont.lvs.ru", "_DND мудак_", $s[8]);
		$s[8]=str_replace("http://www.evrostroika.ru", "_DND мудак_", $s[8]);
		$vars["MESSAGE"]= popup_messageparse($s[8], $pcont==2);

		$vars["spam"]	= ($s[1]=='00:11:22:22:11:00');
		$vars["spam"]	= ('spambot'==$vars["NICK"]) || $vars['spam'];
	
		$output .= theme("popup", $vars);
  	}
	return $output;
}
?>
