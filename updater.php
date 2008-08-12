<?php
header('Content-type: text/javascript');
$request = getallheaders();
if (isset($request['If-Modified-Since'])) {
header('HTTP/1.1 304 Not Modified');
die();
}
header('Expires: '.gmdate('D, d M Y H:i:s', time()+365*24*60*60).'GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
?>
function bla() {
updater.stop();
var response="<tr><td>fdgg</td></tr>";
var lastid=parseInt($("a.browse-link:first").text().substr(1));

response=$.ajax({
  url: "getnew.php?c="+lastid,
  complete: function () {},
  success: function(html){
  $("#u_clock").html("0")
  if (html.length > 1) {
    var html=$(html).css({display:"none"}).fadeIn(2000);
    $('#messages tbody').prepend(html)
    var messages=$("tr.message");
 if (messages.length > 21) $("tr.message:last").remove();
 if (messages.length > 20) $("tr.message:last").fadeOut(1000);
 }
  updater.reset(5000);
  }
 });

}

var updater=$.timer(5000, bla);
var clock=$.timer(1000, function() {
  $("#u_clock").html(parseInt($("#u_clock").html())+1)
});

function updater_toggle(t) {
	if (t) {
	updater.reset(5000);clock.reset(1000);bla()
	document.cookie='updater=1; path=<?php include "inc/config.php"; echo $INFO['cookie_path']?>';
	} else {
	updater.stop(); clock.stop();
	document.cookie='updater=0; path=<?php echo $INFO['cookie_path']?>';
	}
	$("#u_on_off a").toggle()
}


$(document).ready( function () {
	$("#messages").parent().parent().prev().find("td").html('<div class="table_header" id="updater">Автообновление попапов <span id="u_on_off">'+
	'<a href="javascript:updater_toggle(false)">включено</a><a style="display:none" href="javascript:updater_toggle(true)'+
	'">выключено</a></span>. Последнее обновление <span id="u_clock">0</span> сек. назад.</div>')
if (document.cookie.replace('updater=0', '') != document.cookie) {
	updater_toggle(false);
}

})



