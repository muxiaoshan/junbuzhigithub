<? include handler('template')->file('header'); ?>
<?=ui('loader')->css('@jquery.tipTip')?>
<?=ui('loader')->js('@jquery.tipTip')?>
<?=ui('loader')->js('@jquery.hook')?>
<?=ui('loader')->js('@jquery.cache')?>
<?=ui('loader')->js('@jquery.form')?>
<?=ui('loader')->css('@jquery.validationEngine')?>
<?=ui('loader')->js('@jquery.validationEngine.cn')?>
<?=ui('loader')->js('@jquery.validationEngine')?>
<script type="text/javascript">
var productid = "<?=$product['id']?>";
var price = <?=$product['nowprice']?>;
var oncemax = <?=$product['oncemax']?>;
var oncemin = <?=$product['oncemin']?>;
var surplus = <?=$product['surplus']?>;
<? if($product['type'] == 'stuff') { ?>
var weight = <?=$product['weightsrc']?>;
<? } ?>
function order_finish(id)
{
window.location = '<?=rewrite("?mod=buy&code=order&id=")?>'+id;
}
</script>
<div class="m960">
<?=ui('loader')->js('@buy.checkout', UI_LOADER_ONCE)?>
<div class="t_l">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title">提交订单</p>
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
<td width="35%"><?=$product['flag']?></td>
<td width="10%">
<input type="text" name="num_buys" id="num_buys" value="<?=$product['oncemin']?>" maxlength="4" class="input_text f_input2">
</td>
<td width="4%">x</td>
<td width="12%">&yen; <?=$product['nowprice']?></td>
<td width="4%">=</td>
<td width="15%">
<span class="B">&yen; 
<span id="price_product">
<? echo $product['nowprice']*$product['oncemin'] ?>
</span>
</span>
<p id="product_weight" style="color:#999;"></p>
</td>
</tr>
</table>
<? logic('address')->html($product) ?>

<? logic('express')->html($product) ?>

<? logic('notify' )->html($product) ?>
<table class="price_calc">
<tr id="tr_price_total">
<td class="left">
应付总额
</td>
<td class="right" style="width:100px; margin:0px; padding:0px;" >
<span style=" float:left;margin:0px; padding:0px;">	&yen;</span> <font id="price_total" class="price" style="margin:0px; padding:0px;"><? echo $product['nowprice']*$product['oncemin']; ?></font>
</td>
</tr>
</table>
<div class="submit_div">
<form id="checkout_submit" action="?mod=buy&code=checkout&op=save" method="post" >
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<input id="product_id" type="hidden" name="product_id" value="<?=$product['id']?>" />
订单附言：<br/><textarea class="extmsg" name="extmsg"></textarea><br/><br/>
<input id="checkout_submit_button" type="submit" value="确认无误，下订单" name="buy" class="formbutton formbutton_n" />
<font id="submit_status"></font>
</form>
</div>
</div>
</div>
</div>
<div class="l_rc_b" ></div>
</div>
<script type="text/javascript">fizinit();</script>
<div class="t_r">
<?=ui('widget')->load()?>
</div>
</div>
<? include handler('template')->file('footer'); ?>