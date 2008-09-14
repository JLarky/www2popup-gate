<?php
function sendlater($to, $from, $msg, $time=null) {
  global $INFO;

 $to=mysql_real_escape_string($to);
 $from=mysql_real_escape_string($from);
 $msg=mysql_real_escape_string("Sended: ".date("d.m.y H:i:s")."\n".$msg);

 $to_a = explode(" ", $to);
 foreach ($to_a as $to) {
	$to=mysql_real_escape_string($to);
	mysql_query("INSERT INTO `".$INFO['later_tabl']."` (`from`, `to`, `msg`, `counter`, `comment`)
	VALUES ('$from', '$to', '$msg', '0', 'Test') ;");
	echo mysql_error();
 }

}

class PopupSender {
 private $msgto;
 private $msgfrom;
 private $msg;
 function __construct($msgto, $msgfrom, $msg) {
   sendlater($msgto, $msgfrom, $msg);
}
}
?>
