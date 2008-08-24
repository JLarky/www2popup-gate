 <tr>
<td>
<table class="table_header" align="center" width="100%">
<caption>Статистика</caption>
<tbody><tr>
<td align="left">первый попап</td>
<td align="right"><?=$first_popup?></td>
</tr>
<tr>
<td align="left">последний попап</td>
<td align="right"><?=$last_popup?></td>
</tr>
 
<tr>
<td align="left">символов всего</td>
<td align="right"><?=$chars_total?></td>
</tr>
<tr>
<td align="left">попапов всего</td>
<td align="right"><?=$popups_total?></td>
</tr>
<tr>
<td align="left">попапов вчера</td>
<td align="right">{YESTERDAY_POPUP}</td>
 
</tr>
<tr>
<td align="left">попапов сегодня</td>
<td align="right">{TODAY_POPUP}</td>
</tr>
</tbody></table>
<br>
<table class="table_header" align="center" width="100%">
<caption>Топ 20 и одного флудера</caption>
<tbody><tr>
<th align="center">место</th>
<th align="left">отправитель</th>
 
<th align="right">попапов</th>
<th align="right">%</th>
<th align="right">символов</th>
<th align="right">%</th>
<th align="center">последний</th>
</tr>
 
<?php echo $stat ?>
 
</tbody></table></td>
 </tr>