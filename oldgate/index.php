<?php
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$tstart = $mtime;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Description" content="�����-���� 14, 15 � 16 ��������� ����������� �����." />
<meta http-equiv="Pragma" content="no-cache" />
<?php
  $c = 0;
  $u = 0;
  if (isset($_GET['c'])) {
    $c = $_GET['c'];
  }
  if (isset($_GET['u'])) {
    $u = $_GET['u'];
  }
  if ($c <= 20) {
    $pr = 0;
  } else {
    $pr = $c - 20;
  }
  $ne = $c + 20;
  $us = "";
  if ($u == 1) {
    $us = "u=1&amp;";
    $strtl = "<a href=\"gate/?u=1\">to start</a>";
    echo "<meta http-equiv=\"Refresh\" content=\"10; url=gate/?u=1";
    if ($c > 0) {
      echo "&amp;c={$c}";
    }
    echo "\" />";
  } else {
    $strtl = "<a href=\"/gate/\">� ������</a>";
  }
  $nextl = "<a href=\"/gate/?" . $us . "c=" . $pr ."\">��������� 20</a>";
  $prevl = "<a href=\"/gate/?" . $us . "c=" . $ne . "\">���������� 20</a>";
?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>/popup/gate</title>
<link href="/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
<table width="740px" border="0">
<tr><td>
<table width="100%" border="0" class="table_header">
<tr>
<td><div align="center"><a href="/gate/">/gate</a></div></td>
<td><div align="center"><a href="/private/">/private</a></div></td>
<td><div align="center"><a href="/search/">/search</a></div></td>
<td><div align="center"><a href="/send/">/send</a></div></td>
<td><div align="center"><a href="/stat/">/stat</a></div></td>
<td><div align="center"><a href="/about/">/about</a></div></td>
</tr>
</table>
</td></tr>
<tr><td>
<table width="100%" border="0" class="table_header">
<tr>
<td><div align="right"><?php echo $nextl;?></div></td>
<td><div align="center"><?php echo $strtl;?></div></td>
<td><div align="left"><?php echo $prevl;?></div></td>
</tr>
</table>
</td></tr> 
<tr><td>
<table width="100%" border="0" class="table_footer">
<?php          
Error_Reporting(E_ALL & ~E_NOTICE);
  mysql_connect('localhost', 'ODBC', '');
  mysql_select_db('popup');
//  mysql_query("SET NAMES 'cp1251'") or die("Invalid query: " . mysql_error());
//echo "SELECT * FROM p ORDER BY id DESC LIMIT " . $c . ", 20<br>";
  $e = mysql_query("SELECT * FROM popup ORDER BY id DESC LIMIT " . $c . ", 20") or die("!Invalid query: " . mysql_error());
  while($s = @mysql_fetch_row($e)){
    $d1 = $s[1][6].$s[1][7].".".$s[1][4].$s[1][5].".".$s[1][0].$s[1][1].$s[1][2].$s[1][3];
    $d2 = $s[1][8].$s[1][9].":".$s[1][10].$s[1][11].":".$s[1][12].$s[1][13];
    $s[6] = htmlspecialchars($s[6]);
    $s[6] = str_replace ("\r\n", " <br /> ", $s[6]);
    $s[6] = preg_replace("#(https?|ftps?|ed2k|file|mms|news|)://\S+[^\s.,>)\];'\"!?]#", '<a href="\\0" target="_blank">\\0</a>', $s[6]);
    $s[6] = preg_replace("#\\\\\S+[^\s.,>)\];'\"!?]#", '<a href="file://\\0" target="_blank">\\0</a>', $s[6]);
    echo "<tr><td><table width=\"100%\" border=\"0\" class=\"table_header\"><tr>
<td><div align=\"left\"><a title=\"" . $s[4] . "; ip: " . $s[3] . "; mac: " . $s[2] . "\"><b>" . $s[5] . "</b></a> at " . $d2 . " " . $d1 . "</div></td>
<td><div align=\"right\"><a href=\"/send/?id=" . $s[0] . "\">��������</a></div></td>
</tr></table></td></tr>
<tr><td><div align=\"left\"> " . $s[6] . " <br /> <br /> </div></td></tr>
";
  }
?>
</table></td></tr>
<tr><td>
<table width="100%" border="0" class="table_header">
<tr>
<td><div align="right"><?php echo $nextl;?></div></td>
<td><div align="center"><?php echo $strtl;?></div></td>
<td><div align="left"><?php echo $prevl;?></div></td>
</tr>
</table>
</td></tr> 
<tr><td>
<form action="" method="get">
<table width="100%" border="0" class="table_header">
<tr><td><div align="left">
<input type="checkbox" name="u" value="1" 
<?php
if ($u == 1) {
  echo "checked=\"checked\" ";
  }
?>
 /> &lt;- �������������� 
<input name="c" type="hidden" id="c" value="<?php echo $c; ?>" />
<input type="submit" value="����������" />
</div></td>
<td>
<?php
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$tend = $mtime; 
$totaltime = ($tend - $tstart); 
printf ("����� ���������� %.3f ���", $totaltime);
?>
</td>
<td><div align="right">&copy; <a href="mailto:mntek@inbox.ru">mntek</a>, 2006</div></td></tr>
</table>
</form>
</td></tr>
</table>
</div>
</body>
</html>