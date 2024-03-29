<? include handler('template')->file('@admin/header'); ?>
<?=ui('loader')->js('#admin/js/coupon.ops')?>
<div class="header"> <a href="?mod=coupon&code=vlist">团购券列表</a> </div>
<?=ui('isearcher')->load('admin.coupon_list')?>
<div class="export_link"> <a class="button back1 back2 fr" href="?mod=export&code=coupon&referrer=<? echo urlencode($_SERVER['QUERY_STRING']); ?>">导出数据</a> </div> <table id="orderTable" cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <thead> <tr class="tr_nav"> <td width="15%">订单信息</td> <td width="10%">用户信息</td> <td width="8%">团购券号码/密码</td> <td width="16%">过期/使用时间</td> <td width="5%">份数</td> <td width="10%">管理操作</td> </tr> </thead> <tbody> 
<? if(is_array($list)) { foreach($list as $one) { ?>
 <tr id="cp_on_<?=$one['ticketid']?>"> <td>
<?=$one['flag']?>
<br/>
<?=$one['orderid']?>
</td> <td><? echo app('ucard')->load($one['uid']); ?></td> <td>
<?=$one['number']?>
<br/>
<?=$one['password']?>
</td> <td><? echo date('Y-m-d H:i:s', $one['perioddate']); ?><br/>
<? if($one['status'] == TICK_STA_Used) { ?>
<?=$one['usetime']?>
<? } else { ?><? echo logic('coupon')->STA_Name($one['status']); ?>
<? } ?>
</td> <td> <b><?=$one['mutis']?></b> 份
</td> <td>
<? if($one['status'] == TICK_STA_Unused) { ?>
<a href="javascript:couponAlert(<?=$one['ticketid']?>);">[ 消费提醒 ]</a><br/>
<? } ?>
<a href="javascript:couponDelete(<?=$one['ticketid']?>);">[ 删除 ]</a> </td> </tr> 
<? } } ?>
 </tbody> <tfoot> <tr> <td colspan="6"><?=page_moyo()?></td> </tr> </tfoot> </table>
<? include handler('template')->file('@admin/footer'); ?>