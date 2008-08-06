<?php
################################
# output:	{POPUPS}
################################

############
# PROCESSING DATA
static $pcont=0;
  while($s = @mysql_fetch_row($e)){
$pcont++;
//var_dump($pcont);
	$tpl->assign( array( "ID" 	=> $s[0]));
	$tpl->assign( array( "MAC" 	=> $s[1]));
	$tpl->assign( array( "IP" 	=> $s[2]));
	$tpl->assign( array( "COMP" 	=> htmlspecialchars($s[3])));
	$tpl->assign( array( "NICK" 	=> htmlspecialchars($s[4])));
	$tpl->assign( array( "DATE" 	=> $s[9]/*$s[1][6].$s[1][7].".".$s[1][4].$s[1][5].".".$s[1][0].$s[1][1]
	.$s[1][2].$s[1][3]." ". $s[1][8].$s[1][9].":".$s[1][10].$s[1][11].":".$s[1][12].$s[1][13]*/));
	$tpl->assign( array( "TO" 	=> htmlspecialchars($s[6])));
	$s[6] = htmlspecialchars($s[8]);
	$s[6] = str_replace ("{", "&#123;", $s[6]);
	$s[6] = str_replace ("}", "&#125;", $s[6]);
 	$s[6] = preg_replace("#(https?|ftps?|ed2k|file|smb|mms|news)://\S+[^\s.,>)\];'\"!?]#", '<a href="\\0" target="_blank">\\0</a>', $s[6]);
	$s[6] = str_replace ("\r", "", $s[6]);
	$s[6] = str_replace ("\n", "<br />", $s[6]);

	   //$s[6] = preg_replace("#\\\\\S+[^\s.,>)\];'\"!?]#", '<a href="file://\\0" target="_blank">\\0</a>', $s[6]);
	if (!$USER["user_id"] and $_SERVER["HTTP_HOST"]=="jlarky.dorms.spbu.ru" and $pcont==2 and (!isset($_REQUEST['no_spam']) or (isset($_REQUEST['no_spam']) and $_REQUEST['no_spam']!='1'))) {
	$tpl->assign( array( "MESSAGE"	=> $s[6].'<br />
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
</div>'));
	} else {
	$tpl->assign( array( "MESSAGE"	=> $s[6]));	
	}
	$tpl->parse("POPUP", 	array("popup"));
	@$tpl->parse("POPUPS", ".popup");
  };
?>