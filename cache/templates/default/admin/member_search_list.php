<? include handler('template')->file('@admin/header'); ?>
 <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td>技巧提示</td> </tr> <tr bgcolor="#F4F8FC"> <td> <ul> <li>系统角色为<b>粗体</b>显示，自定义角色为普通。</li> </ul> </td> </tr> </table> <br> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <form method="post"  action="<?=$action?>">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <tr align="center" class="header"> <td width="48"> <input type="checkbox" name="chkall" class="header" onclick="checkall(this.form, 'ids')" class="checkbox">全删</td> <td>用户名</td> <td>余额</td> <td>总消费</td> <td>角色</td> <td>操作</td> </tr> 
<? if(is_array($member_list)) { foreach($member_list as $member) { ?>
 <tr onmouseover="this.className='tr_hover'" onmouseout="this.className='tr_normal'"> <td><input type="checkbox" name="ids[]" value="<?=$member['uid']?>" class="checkbox">删</td> <td><a href="admin.php?mod=member&code=modify&id=<?=$member['uid']?>"><? echo app('ucard')->load($member['uid']); ?></a></td> <td><?=$member['money']?>元</td> <td><?=$member['totalpay']?>元</td> <td><?=$member['role_name']?></td> <td> <a href="admin.php?mod=member&code=modify&id=<?=$member['uid']?>">[编辑]</a> <a href="admin.php?mod=member&code=dodelete&ids=<?=$member['uid']?>">[删除]</a> </td> </tr> 
<? } } ?>
 
<? if($pages) { ?>
 <tr> <td colspan="20"><?=$pages?></td> </tr> 
<? } ?>
 </table> <br> <center><input type="submit" name="editsubmit" value="提 交" class="button"></center> </form>
<? include handler('template')->file('@admin/footer'); ?>