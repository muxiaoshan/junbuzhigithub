<? include handler('template')->file('@admin/header'); ?>
<?=ui('loader')->js('#admin/js/zlog.b64')?>
<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="7">
日志中心 &gt;&gt; 搜索结果 / [ <a href="admin.php?mod=zlog&type=<?=$type?>&index=<?=$index?>&name=<?=$name?>&extra=<?=$extra?>&time=<?=$time?>">返回到搜索页面</a> ]
</td> </tr> <tr class="tr_nav"> <td width="10%">编号</td> <td width="10%">类型</td> <td width="12%">索引号</td> <td>标题</td> <td width="10%">触发者</td> <td width="10%">触发IP</td> <td width="12%">触发时间</td> </tr>
<? if($list) { ?>
<? if(is_array($list)) { foreach($list as $log) { ?>
 <tr class="zlog_tr"> <td class="zlog_extra_link"><?=$log['id']?></td> <td><?=$log['type']?></td> <td><?=$log['index']?></td> <td><?=$log['name']?></td> <td><? echo app('ucard')->load($log['uid']); ?></td> <td><? echo long2ip($log['uip']); ?></td> <td><? echo date('Y-m-d H:i:s', $log['time']); ?></td> </tr> <tr> <td colspan="7" class="zlog_extra">
<?=$log['extra']?>
</td> </tr> <tr class="zlog_tr"><td colspan="7"></td></tr> 
<? } } ?>
<? } else { ?><tr class="tips"> <td colspan="7">
没有搜索到相关日志！
</td> </tr>
<? } ?>
<tr> <td colspan="7">
<?=page_moyo()?>
</td> </tr> <tr> <td colspan="7"> <a href="javascript:history.go(-1);">返回到上一页</a> </td> </tr> </table>
<? include handler('template')->file('@admin/footer'); ?>