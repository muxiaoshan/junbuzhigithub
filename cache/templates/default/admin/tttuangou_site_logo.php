<? include handler('template')->file('@admin/header'); ?>
 <script type="text/javascript">
function reSizeImg(obj)
{
if (obj.width > 230)
{
obj.width = 230;
}
}
</script> <form name="logoform" method="post"  enctype="multipart/form-data">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="4">站点Logo设置</td> </tr> <tr class="alert"> <td colspan="4">所有风格模板的LOGO大小为<b>230x66</b>  ，请不要上传分辨率过大的图片！</td> </tr> <tr> <td width="10%">描述</td> <td width="20%">Logo</td> <td width="12%">路径</td> <td width="15%">上传/替换</td> </tr> 
<? if(is_array($logos)) { foreach($logos as $i => $logo) { ?>
 <tr onmouseover="this.className='tr_hover'" onmouseout="this.className='tr_normal'"> <td><?=$logo['title']?></td> <td><img onload="reSizeImg(this)" src="<?=$logo['url']?>" /></td> <td><?=$logo['url']?></td> <td> <input type="file" name="uploads[]" /> <input type="submit" class="button" value="保存" onclick="document.logoform.action='admin.php?mod=tttuangou&code=dositelogo&op=save&path=<? echo urlencode($logo['url']); ?>';" /> </td> </tr> 
<? } } ?>
 <tr class="footer"> <td colspan="4"><b>提示：每次只能上传一张图片，请不要尝试进行批量上传替换！</b></td> </tr> </table> </form>
<? include handler('template')->file('@admin/footer'); ?>