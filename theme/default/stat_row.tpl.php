<tr>
<td align="center"><?=$num?></td>
<td align="left"><b><?=$nick?></b></td>
<td align="right"><?=$popups?></td>
<td align="right"><?=sprintf("%.2f", round(100*$popups/$popups_total, 2))?></td>
 
<td align="right"><?=$chars?></td>
<td align="right"><?=sprintf("%.2f", round(100*$chars/$chars_total, 2))?></td>
<td align="center"><?=$last?></td>
</tr>