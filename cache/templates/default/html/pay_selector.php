<?=ui('loader')->css('@pay.selector')?>
<? $me_money = round(user()->get('money')*1, 2);
$allowSelfPay = false;
$pay_money = round($pay_money, 2);
$remain_money = $pay_money - $me_money;
$dspSelfPay = $product_type == 'recharge' ? false : true;
 ?>
<div id="paytype_list" class="paytype_list">
<p class="title">请选择支付方式</p>
<table width="100%" border="0" class="P_diso">
<tr>
<td colspan="3">
<p class="P_diso_1">
<span style="float:left">您当前账户余额：<?=$me_money?> 元</span>
<? if($dspSelfPay) { ?>
<span style="float:right">
<? if($me_money > 0 && $me_money < $pay_money) { ?>
<label><input type="checkbox" id="payment_use_surplus" value="true" />使用余额支付</label><? } elseif($me_money >= $pay_money) { ?><? $allowSelfPay = true ?>
可以使用余额付款
<? } ?>
</span>
<? } ?>
</p>
<? if($dspSelfPay) { ?>
<p class="P_diso_2">
<font id="payment_total_money">您总共需要支付<?=$pay_money?>元</font>
<font id="payment_remain_money">扣除您帐户的<?=$me_money?>元后，您还需要支付<?=$remain_money?>元</font>
<div id="payment_clear"></div>
</p>
<? } ?>
</td>
</tr>
<? if($product_type == 'recharge') { ?>
<? $allowSelfPay = false ?>
<? } ?>

<? if($product_id) { ?>
<? $listString = meta('paymentlist_of_'.$product_id) ?>
<? } else { ?><? $listString = false ?>
<? } ?>
<? if(is_array(logic('pay')->GetList())) { foreach(logic('pay')->GetList() as $i => $pay) { ?>
<? if(!$allowSelfPay && $pay['code'] == 'self') { ?>
<? continue ?>
<? } ?>

<? if($pay['code'] == 'cod' && $product_type != 'stuff') { ?>
<? continue ?>
<? } ?>

<? if($pay_money <=0 && $pay['code'] != 'self') { ?>
<? continue ?>
<? } ?>

<? if($product_type == 'recharge' && $pay['code'] == 'bank') { ?>
<? continue ?>
<? } ?>

<? if($product_type != 'recharge' && $pay['code'] == 'recharge') { ?>
<? continue ?>
<? } ?>

<? if($listString) { ?>
<? if(!stristr($listString, $pay['code'].',')) { ?>
<? continue ?>
<? } ?>
<? } ?>
<tr class="pay_tr" style="cursor: pointer;" onmouseover="pay_tr_mouseover(this)" onmouseout="pay_tr_mouseout(this)" onclick="pay_tr_click('<?=$pay['id']?>')">
<td width="4%"><input id="payment_id_<?=$pay['id']?>" name="payment_id" type="radio" value="<?=$pay['id']?>" /></td>
<td width="20%"><img src="static/images/pay/<?=$pay['code']?>.gif" /></td>
<td width="66%"><?=$pay['detail']?></td>
</tr>
<? } } ?>
</table>
</div>
<?=ui('loader')->js('@pay.selector')?>