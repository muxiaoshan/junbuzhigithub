<? include handler('template')->file('@admin/header'); ?>
<?=ui('loader')->js('#admin/js/sdb.parser')?>
<script type="text/javascript">
function clearNowStyle()
{
var name = 'stylesheet';
var value = '';
var expiredays = -1;
exp = new Date();
exp.setTime(exp.getTime() + (86400 *expiredays));
document.cookie = name + "=" + escape(value) + "; expires=" + exp.toGMTString() + "; path=/";
}
</script> <form method="post"  action="<?=$action?>">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="3">默认风格设置 </td> </tr> <tr> <td colspan="3">
是否禁用多风格切换功能？ <font class="ini" src="product.disabled_multi_style"></font> </td> </tr> <tr class="tr_nav"> <td width="20%">预览图</td> <td width="12%">名称</td> <td width="15%">状态</td> </tr> 
<? if(is_array($styles)) { foreach($styles as $i => $style) { ?>
 <tr> <td><img src="<?=$style['preview']?>" style="margin:5px 0;"/></td> <td><?=$style['name']?></td> <td>
<? if($style['flag'] == $default_style) { ?>
<font class="fred">当前默认风格</font>
<? } else { ?><a href="admin.php?mod=tttuangou&code=dodefaultstyle&op=set&id=<?=$i?>" onClick="clearNowStyle();">设为默认</a>
<? } ?>
</td> </tr> 
<? } } ?>
 </table> </form>
<? include handler('template')->file('@admin/footer'); ?>