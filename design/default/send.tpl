<tr><td>
{SENDLOG}
<div align="center">
<form method="post" action="index.php?act=send">
<table width="100%" border="0">
<tr>
<td align="right" width="50%">ваш ник-&gt;<input name="P_F" type="text" class="table_header"
value="{P_F}" size="32" maxlength="50" /></td>
<td align="left" width="50%"><input name="P_T" type="text" class="table_header" 
value="{P_T}" size="32" maxlength="70" />&lt;- кому</td>
</tr>
<tr><td></td><td align="left"></td></tr>
</table>
<table width="80%" border="0">
<tr>
<td><div align="center">
<p>&lt;- message -&gt;<br />

<textarea name="P_M" cols="70" rows="15" class="table_header">{MESSAGE}</textarea>
</p><p>
<input type="submit" value="отправить"><input type="submit" name="sendlater" value="добавить в очередь"><input type="reset" value="сбросить" />
</p>
</div></td>
</tr>
</table>
</form>
</div>
</td></tr>