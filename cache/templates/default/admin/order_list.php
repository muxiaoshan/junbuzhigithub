<? include handler('template')->file('@admin/header'); ?>
 <div class="header"> <a href="?mod=order&code=vlist"> 订单列表 </a> </div>
<?=ui('isearcher')->load('admin.order_list')?>
<div class="export_link"> <a class="button  back1 back2 fr" href="?mod=export&code=order&referrer=<? echo urlencode($_SERVER['QUERY_STRING']); ?>">导出数据</a> </div> <table id="orderTable" cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <thead> <tr class="tr_nav"> <td width="15%">订单信息</td> <td width="10%">用户信息</td> <td width="10%">下单时间</td> <td width="10%">订单金额</td> <td width="10%">实付金额</td> <td width="10%">订单操作</td> </tr> </thead> <tbody> 
<? if(is_array($list)) { foreach($list as $one) { ?>
 <tr> <td>
<?=$one['product']['flag']?>
<br/>
<?=$one['orderid']?>
</td> <td><? echo app('ucard')->load($one['userid']); ?></td> <td><? echo timebefore($one['buytime']); ?></td> <td>
￥<? echo $one['productprice']*$one['productnum']+$one['expressprice']; ?></td> <td>
<? if($one['pay']==1) { ?>
￥<?=$one['paymoney']?>
<? } else { ?>还未支付
<? } ?>
</td> <td>
<? if($one['status'] == ORD_STA_Normal) { ?>
<? echo logic('order')->PROC_Name($one['process']); ?>
<? } else { ?><? echo logic('order')->STA_Name($one['status']); ?>
<? } ?>
<br/> <a href="?mod=order&code=process&id=<?=$one['orderid']?>&referrer=<? echo urlencode($_SERVER['QUERY_STRING']); ?>">[ 处理订单 ]</a> </td> </tr> 
<? } } ?>
 </tbody> <tfoot> <tr> <td colspan="6"><?=page_moyo()?></td> </tr> </tfoot> </table> <script type="text/javascript">
$(document).ready(function(){
$('#iscp_frc_ordproc').after('&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$batchURL?>"><input type="button" class="service" value="批量处理" /></a>');
});
</script>
<? include handler('template')->file('@admin/footer'); ?>