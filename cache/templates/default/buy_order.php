<? include handler('template')->file('header'); ?>
<?=ui('loader')->css('@jquery.tipTip')?>
<?=ui('loader')->js('@jquery.tipTip')?>
<?=ui('loader')->js('@jquery.hook')?>
<?=ui('loader')->js('@jquery.form')?>
<script type="text/javascript">
function order_finish()
{
window.location = '<? echo rewrite("?mod=buy&code=pay&id=".$order["orderid"]); ?>';
}
</script>
<div class="m960">
<div class="t_l">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title">请确认订单</p>
<div class="sect">
<table id="report">
<tr>
<th>团购项目</th>
<th>数量</th>
<th>&nbsp;</th>
<th>价格</th>
<th>&nbsp;</th>
<th>总价</th>
</tr>
<tr class="Bor">
<td width="35%"><?=$order['product']['flag']?></td>
<td width="10%"><?=$order['productnum']?></td>
<td width="4%">x</td>
<td width="12%">&yen; <?=$order['productprice']?></td>
<td width="4%">=</td>
<td width="15%"><span class="B">&yen; <?=$order['price_of_product']?></span></td>
</tr>
</table>
<? logic('address')->html($order) ?>

<? logic('express')->html($order) ?>
<div class="nleftL">
<p class="P_disa">总金额：<?=$order['price_of_total']?>元</p>
</div>
<? logic('pay')->html($order) ?>
<div class="submit_div">
<form id="order_submit" action="?mod=buy&code=order&op=save" method="post" >
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<input id="order_id" name="order_id" type="hidden" value="<?=$order['orderid']?>" />
<input id="order_submit_button" type="submit" class="formbutton formbutton_check" value="确认订单，并支付" />
<font id="submit_status"></font>
</form>
</div>
</div>
</div>
</div>
<div class="l_rc_b" ></div>
</div>
<?=ui('loader')->js('@buy.order', UI_LOADER_ONCE)?>
<script type="text/javascript">fizinit();</script>
<div class="t_r">
<?=ui('widget')->load()?>
</div>
</div>
<? include handler('template')->file('footer'); ?>