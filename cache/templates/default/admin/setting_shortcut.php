<? include handler('template')->file('@admin/header'); ?>
 <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td>友情提示</td> </tr> <tr class="altbg1"> <td><ul> <li>您可以在下面勾选想出现在后台首页的快捷方式</li> </ul></td> </tr> </table> <form method="post"  name="smtp" action="<?=$action?>">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <a name="快捷方式设置"></a> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="2">快捷方式设置</td> </tr> 
<? if(is_array($menu_list)) { foreach($menu_list as $m_key => $m_val) { ?>
 
<? if($m_val['sub_menu_list'] && is_array($m_val['sub_menu_list']) && count($m_val['sub_menu_list'])) { ?>
 <tr> <td class="altbg1" width="145"><?=$m_val['title']?></td> <td class="altbg2">
<? if(is_array($m_val['sub_menu_list'])) { foreach($m_val['sub_menu_list'] as $s_m_key => $s_m_val) { ?>
 
<? $_checked=$s_m_val['shortcut']?' checked ':''; ?>
 <input type="checkbox" id="menu_list_<?=$m_key?>_<?=$s_m_key?>" name="menu_list[<?=$m_key?>][<?=$s_m_key?>][shortcut]" value="1" <?=$_checked?> /> <label for="menu_list_<?=$m_key?>_<?=$s_m_key?>"><?=$s_m_val['title']?></label>
&nbsp;
<? } } ?>
 </td> </tr> 
<? } ?>
 
<? } } ?>
 </table> <br> <center> <input type="submit" class="abutton" name="settingsubmit" value="提 交"> </center> <br> </form> <br>
<? include handler('template')->file('@admin/footer'); ?>