<?php
function sendlater($to, $from, $msg, $time=null) {
  global $INFO;
var_dump($to, $from, $msg, 'Пока не работает');
 $to=mysql_real_escape_string($to);
 $from=mysql_real_escape_string($from);
 $msg=mysql_real_escape_string($msg);
 mysql_query("INSERT INTO `".$INFO['later_tabl']."` (`from`, `to`, `msg`, `counter`, `comment`)
VALUES ('$from', '$to', '$msg', '0', 'Test') ;");
 echo mysql_error();

}

class PopupSender {
 private $msgto;
 private $msgfrom;
 private $msg;
 function __construct($msgto, $msgfrom, $msg) {
  $this->msgto=escapeshellcmd($msgto);
  $this->msgfrom=escapeshellcmd($msgfrom);
  $this->msg=$msg;

$tmpfname = tempnam("/tmp/psend", "sendl8_");
chmod($tmpfname, 444);

$handle = fopen($tmpfname, "w");
fwrite($handle, "Sended: ".date("d.m.y H:i:s"));
fwrite($handle, "\n");
fwrite($handle, stripslashes($this->msg));
fclose($handle);
system("touch /var/www/gate/bin/tosend.add");
system("chmod a+r /var/www/gate/bin/tosend.add");
$handle = fopen("/var/www/gate/bin/tosend.add", "a");
fwrite($handle, $this->msgto." ".$this->msgfrom." ".substr($tmpfname,strlen("/tmp/psend/"))."\n");
fclose($handle);
}
}
?>
