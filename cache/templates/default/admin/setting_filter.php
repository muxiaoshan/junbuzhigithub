<? include handler('template')->file('@admin/header'); ?>
<form method="post"  action="admin.php?mod=setting&code=domodify_filter">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table width="100%" border="0" cellpadding="0" cellspacing="0" class="tableborder"> <tr class="header"> <td colspan="5">内容过滤设置</td> </tr> <tr> <td class="altbg1" width="40%"><b>启用过滤功能</b><br> <span class="smalltext">开启后会对用户发布内容的进行过滤，<br>有助于规避违法内容和广告信息。 </span></td> <td class="altbg2">	<?=$enable_radio?>	</td> </tr> <tr> <td class="altbg1" ><b>违法关键词过滤</b><br> <span class="smalltext">包含设置的关键词时读取的内容将被过滤,同时不允许用户发布含有相关关键词的信息。
<br>(关键词一行一个，或用 "|" 隔开)</span></td> <td class="altbg2"> <img src="./templates/default/images/admincp/zoomin.gif" onmouseover="this.style.cursor='pointer'" onclick="zoomtextarea('filter[keywords]', 1)"> <img src="./templates/default/images/admincp/zoomout.gif" onmouseover="this.style.cursor='pointer'" onclick="zoomtextarea('filter[keywords]', 0)"> <br /> <textarea cols="80" rows="10" name="filter[keywords]"><?=$filter['keywords']?></textarea> </td> </tr> </table> <br> <center><input class="button" type="submit" name="cronssubmit" value="提 交"></center> </form>
<? include handler('template')->file('@admin/footer'); ?>