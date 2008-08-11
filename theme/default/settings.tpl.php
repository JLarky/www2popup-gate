 <tr><td>
<form method=POST>
<table width="100%">
<tr>
		 <td colspan=2>
<p>Персональне настройки</p>
           </td>
</tr>
<tr>
	<td width="50%">
		Писать ли заголовок с айпи:
	</td>
	<td>
		<input name="head" <?=$set_header?> value="1" type="checkbox">
	</td> 
</tr>
<tr>
	<td width="50%">
		Показыавть отправленные сообщения:
	</td>
	<td>
		<input name="set_outgoing" <?=$set_outgoing?> value="1" type="checkbox">
	</td> 
</tr>
<!--<tr>
	<td>
		Password:
	</td>
	<td>
		<input type=password name=pass>
	</td> 
</tr> -->
<tr>
	<td>
	</td>
	<td>
<input type=submit name=settings value=Set>
	</td>
</tr>
</table>
</form>
 </td></tr>