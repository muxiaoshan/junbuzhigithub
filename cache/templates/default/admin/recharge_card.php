<? include handler('template')->file('@admin/header'); ?>
<?=ui('loader')->js('#admin/js/recharge.card.ops')?>
<div class="header"> <a href="?mod=recharge&code=card">充值卡列表</a> <font id="recharge_order_clean"></font> </div>
<?=ui('isearcher')->load('admin.recharge_card_list')?>
<table id="orderTable" cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <thead> <tr class="tr_nav"> <td width="15%">充值卡号码</td> <td width="10%">充值卡密码</td> <td width="6%">面额</td> <td width="16%">使用状态</td> <td width="8%">管理</td> </tr> </thead> <tbody> 
<? if(is_array($list)) { foreach($list as $one) { ?>
 <tr id="rc_on_<?=$one['id']?>"> <td>
<?=$one['number']?>
</td> <td>
<?=$one['password']?>
</td> <td>
<?=$one['price']?>
</td> <td>
<? if($one['usetime'] > 0) { ?>
已经使用<br/>
使用时间：<? echo date('Y-m-d H:i:s', $one['usetime']); ?><br/>
使用者：<? echo app('ucard')->load($one['uid']); ?>
<? } else { ?>还未使用
<? } ?>
</td> <td> <a href="javascript:rechargeCardDelete(<?=$one['id']?>);">[ 删除 ]</a> </td> </tr> 
<? } } ?>
 </tbody> <tfoot> <tr class="footer"> <td colspan="5"><a href="admin.php?mod=recharge&code=card&op=generate">生成充值卡</a></td> </tr> <tr> <td colspan="5"><?=page_moyo()?></td> </tr> </tfoot> </table>
<? include handler('template')->file('@admin/footer'); ?>