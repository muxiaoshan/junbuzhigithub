<? include handler('template')->file('@admin/header'); ?>
 <form action="?mod=upload&code=config&op=save" method="post" >
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="2">上传设置</td> </tr> <tr> <td width="30%"></td> <td width=""></td> </tr> <tr> <td class="td_title">允许上传类型：</td> <td> <input type="text" name="exts" value="<?=$upcfg['exts']?>" />
&nbsp;&nbsp;&nbsp;*多个使用英文逗号间隔
</td> </tr> <tr> <td class="td_title">允许上传文件尺寸：</td> <td> <input type="text" name="size_val" value="<?=$size_val?>" /> <select name="size_unit"> <option value="kb"<? echo $size_unit=='kb'?' selected="selected"':''; ?>>KB</option> <option value="mb"<? echo $size_unit=='mb'?' selected="selected"':''; ?>>MB</option> </select> </td> </tr> <tr> <td class="td_title">允许使用上传功能的角色</td> <td> <select name="role[]" multiple="multiple">
<? if(is_array($sys_roles)) { foreach($sys_roles as $i => $role) { ?>
<option value="<?=$role['id']?>"<? echo false!==array_search($role['id'], $sel_roles)?' selected="selected"':''; ?>><?=$role['name']?></option>
<? } } ?>
</select>
*按住 CTRL 并点击角色名称可以进行多选
</td> </tr> <tr> <td colspan="2" class="tr_center"> <input type="submit" class="button" value="保存" /> </td> </tr> </table> </form> 
<? include handler('template')->file('@admin/footer'); ?>