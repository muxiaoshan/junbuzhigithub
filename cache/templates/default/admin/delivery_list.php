<? include handler('template')->file('@admin/header'); ?>
 <div class="header">
商品发货区 
<font class="dss_block">
查看：
<a href="?mod=delivery&code=vlist"
<? if($alsend==DELIV_SEND_ANY) { ?>
 class="dss_view"
<? } ?>
>已经完成（已确认收货）</a> |
<a href="?mod=delivery&code=vlist&alsend=no"
<? if($alsend==DELIV_SEND_No) { ?>
 class="dss_view"
<? } ?>
>等待发货</a> |
<a href="?mod=delivery&code=vlist&alsend=yes"
<? if($alsend==DELIV_SEND_Yes) { ?>
 class="dss_view"
<? } ?>
>已经发货（等待确认收货）</a> </font> </div>
<?=ui('isearcher')->load('admin.delivery_list')?>
<div class="export_link"> <a class="button  back1 back2 fr" href="?mod=export&code=delivery&referrer=<? echo urlencode($_SERVER['QUERY_STRING']); ?>">导出数据</a> </div> <table id="orderTable" cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <thead> <tr class="tr_nav"> <td width="15%">订单信息</td> <td width="16%">用户信息</td> <td width="12%">配送方式</td> <td width="10%">送货地区</td> <td width="10%">
<? if($alsend == DELIV_SEND_ANY) { ?>
支付时间<? } elseif($alsend == DELIV_SEND_Yes) { ?>发货时间<? } elseif($alsend == DELIV_SEND_No) { ?>等待时间
<? } ?>
</td> <td width="10%">管理操作</td> </tr> </thead> <tbody> 
<? if(is_array($list)) { foreach($list as $one) { ?>
 <tr> <td>
<?=$one['product']['flag']?>
<br/>
<?=$one['orderid']?>
</td> <td><? echo app('ucard')->load($one['userid']); ?></td> <td>
<?=$one['express']['name']?>
</td> <td>
<?=$one['address']['region']?>
</td> <td>
<? if($alsend == DELIV_SEND_ANY) { ?>
<? echo date('Y-m-d H:i:s', $one['paytime']); ?><? } elseif($alsend == DELIV_SEND_Yes) { ?><? echo date('Y-m-d H:i:s', $one['expresstime']); ?><? } elseif($alsend == DELIV_SEND_No) { ?><? echo timebefore($one['paytime'], true); ?>
<? } ?>
</td> <td> <a href="?mod=delivery&code=process&oid=<?=$one['orderid']?>">[ 处理 ]</a> </td> </tr> 
<? } } ?>
 </tbody> <tfoot> <tr> <td colspan="6"><?=page_moyo()?></td> </tr> </tfoot> </table> 
<? include handler('template')->file('@admin/footer'); ?>