<?php
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
