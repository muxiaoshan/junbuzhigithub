<? include handler('template')->file('@admin/header'); ?>
 <div class="export_link"> <a class="button back1 back2 fr" href="<?=ihelper('tg.help.express')?>" target="_blank">使用帮助</a> </div> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr> <td colspan="5"> <div class="is_current">配送方式列表</div> <div class="is_button"><a href="?mod=express&code=corp&op=list">快递公司及快递单模板</a></div> <div class="is_button"><a href="?mod=express&code=address&op=list">寄件人信息管理</a></div> </td> </tr> <tr class="tr_nav"> <td width="10%">编号</td> <td width="30%">配送方式</td> <td width="20%">状态</td> <td width="10%">优先级</td> <td width="10%">管理</td> </tr> 
<? if(empty($list)) { ?>
 <tr><td colspan="5">暂时还没有任何配送方式！</td></tr> 
<? } else { ?> 
<? if(is_array($list)) { foreach($list as $i => $value) { ?>
 <tr> <td><?=$value['id']?></td> <td><?=$value['name']?></td> <td><? echo ($value['enabled']=='true')?'已启用':'未启用'; ?></td> <td><?=$value['order']?></td> <td> <a href="?mod=express&code=edit&id=<?=$value['id']?>">编辑</a>
&nbsp;&nbsp;-&nbsp;&nbsp;
<a href="?mod=express&code=del&id=<?=$value['id']?>" onclick="return confirm('确认删除吗？')">删除</a> </td> </tr> 
<? } } ?>
 
<? } ?>
 <tr> <td colspan="5"><a href="?mod=express&code=add" class="back1 back2">添加</a></td> </tr> </table>
<? include handler('template')->file('@admin/footer'); ?>