<? include handler('template')->file('@admin/header'); ?>
 <form method="post"  action="<?=$action?>">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="6">首页导航设置</td> </tr> <tr class="tr_nav"> <td width="5%">排序</td> <td width="15%">导航名称</td> <td width="30%">连接地址</td> <td width="30%">连接提示</td> <td width="10%">打开方式</td> <td width="10%">管理</td> </tr> 
<? if(!empty($navs)) { ?>
 
<? if(is_array($navs)) { foreach($navs as $i => $nav) { ?>
 <tr id="il_<?=$i?>" onmouseover="this.className='tr_hover'" onmouseout="this.className='tr_normal'"> <td><input type="text" name="order[]" size="3" value="<?=$nav['order']?>"/></td> <td><input type="text" name="name[]" size="13" value="<?=$nav['name']?>"/></td> <td><input type="text" name="url[]" size="30" value="<?=$nav['url']?>"/></td> <td><input type="text" name="title[]" size="30" value="<?=$nav['title']?>"/></td> <td><select name="target[]"><option value="_self"
<? if($nav['target']=='_self') { ?>
 selected="selected"
<? } ?>
>当前窗口</option><option value="_blank"
<? if($nav['target']=='_blank') { ?>
 selected="selected"
<? } ?>
>新建窗口</option></select></td> <td><a href="#void" onclick="javascript:$('#il_<?=$i?>').remove();return false;;">删除</a></td> </tr> 
<? } } ?>
 
<? } ?>
 <tr> <td colspan="6" bgcolor="white"><b>新增</b></td>（天天团购系统相关地址可以输入?开始的url地址，外部网站地址请输入以http://开头的完整地址）
</tr> <tr onmouseover="this.className='tr_hover'" onmouseout="this.className='tr_normal'"> <td><input type="text" name="order[]" size="3"/></td> <td><input type="text" name="name[]" size="13"/></td> <td><input type="text" name="url[]" size="30"/></td> <td><input type="text" name="title[]" size="30"/></td> <td><select name="target[]"><option value="_self">当前窗口</option><option value="_blank">新建窗口</option></select></td> <td></td> </tr> </table> <center> <input type="submit" class="button" value="保存" /> </center> </form>
<? include handler('template')->file('@admin/footer'); ?>