<?php
$fp = fopen($filename, "r");
@$content .= fread( $fp, filesize( $filename ));
fclose( $fp );
	$tpl->assign(array( "CONTENT"	=> $content));
?>