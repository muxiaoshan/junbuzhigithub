<? include handler('template')->file('@admin/header'); ?>
<form action="?mod=widget&code=block&op=config_save" method="post"  enctype="multipart/form-data">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
<tr class="header1" >
<td colspan="3" > <a class="nav_a" href="?mod=widget">布局管理</a> <a class="nav_a selected" href="?mod=widget&code=block">模块管理</a></td>
</tr>
<tr class="header">
<td colspan="2">客服信息设置</td>
</tr>
<tr>
<td width="23%" bgcolor="#F8F8F8"><strong>QQ:</strong>
</td>
<td width="77%" align="right">
<input size="50" name="data[qq]" type="text" id="qq" value="<?=ini("data.cservice.qq")?>" /> （多个QQ请使用逗号间隔）
</td>
</tr>
<tr>
<td width="23%" bgcolor="#F8F8F8"><strong>MSN:</strong>
</td>
<td width="77%" align="right">
<input size="50" name="data[msn]" type="text" id="msn" value="<?=ini("data.cservice.msn")?>" />
</td>
</tr>
<tr>
<td width="23%" bgcolor="#F8F8F8"><strong>阿里旺旺:</strong>
</td>
<td width="77%" align="right">
<input size="50" name="data[aliww]" type="text" id="aliww" value="<?=ini("data.cservice.aliww")?>" />
</td>
</tr>
<tr>
<td width="23%" bgcolor="#F8F8F8"><strong>电话:</strong>
</td>
<td width="77%" align="right">
<input size="50" name="data[phone]" type="text" id="phone" value="<?=ini("data.cservice.phone")?>" />
</td>
</tr>
</table>
<br/>
<center>
<input type="hidden" name="flag" value="cservice" />
<input type="submit" class="button" name="submit" value="保存">
<a href="?mod=widget&code=block" class="back1" >返回</a>
</center>
</form>
<? include handler('template')->file('@admin/footer'); ?>