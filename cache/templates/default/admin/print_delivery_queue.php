<? include handler('template')->file('@admin/header'); ?>
<?=ui('loader')->js('#admin/js/delivery.process')?>
<div class="header">
快递单打印队列
</div> <table id="orderTable" cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <thead> <tr> <td colspan="5">
寄件人：
<? $addresses = logic('express')->cdp()->AddressList() ?>

<? $lastADDR = meta('cdp_service_lastADDR') ?>
<? if($addresses) { ?>
<select id="cdpAddressID">
<? if(is_array($addresses)) { foreach($addresses as $address) { ?>
<option value="<?=$address['id']?>"
<? if($lastADDR==$address['id']) { ?>
 selected="selected"
<? } ?>
><?=$address['name']?> - <?=$address['phone']?></option>
<? } } ?>
</select>
<? } else { ?>您还没有填写寄件人信息，请先 <a href="?mod=express&code=address&op=list">点击此处</a> 进行添加（必须先指定寄件人信息后才可以进行快递单打印）
<? } ?>
</td> </tr> <tr class="tr_nav"> <td width="15%">订单信息</td> <td width="10%">配送方式</td> <td width="10%">收货人</td> <td width="16%">收货地址</td> <td width="10%">快递单号</td> </tr> </thead> <tbody> 
<? if(is_array($list)) { foreach($list as $one) { ?>
 <tr> <td>
<?=$one['product']['flag']?>
<br/>
<?=$one['orderid']?>
<br/> <a href="?mod=delivery&code=process&oid=<?=$one['orderid']?>">[ 查看详情 ]</a> </td> <td>
<?=$one['express']['name']?>
</td> <td>
<?=$one['address']['name']?><br/><?=$one['address']['phone']?>
</td> <td>
<?=$one['address']['region']?><br/><?=$one['address']['address']?>
</td> <td>
<? if($one['process'] != 'TRADE_FINISHED') { ?>
<input id="tno_<?=$one['orderid']?>" type="text" size="32" value="<?=$one['invoice']?>" /> <br/> <input type="button" value="<? echo $one['invoice']==''?'填写':'修改'; ?>快递单号" onclick="javascript:submitTrackingNo(this, '<?=$one['orderid']?>', $('#tno_<?=$one['orderid']?>').val());" />
<? } else { ?><?=$one['invoice']?>
<? } ?>
&nbsp;&nbsp;&nbsp;
<? $printed = logic('express')->cdp()->Printed($one['orderid']) ?>
<input type="button" value="打印快递单" onclick="javascript:cdpServiceOpen('<?=$one['orderid']?>');" style="border-color:<? echo $printed?'#ccc':'#019400'; ?>;" />&nbsp;&nbsp;<font color="#999"><? echo $printed?'已打印':'未打印'; ?></font> </td> </tr> 
<? } } ?>
 </tbody> <tfoot> <tr> <td colspan="5"><?=page_moyo()?></td> </tr> </tfoot> </table> 
<? include handler('template')->file('@admin/footer'); ?>