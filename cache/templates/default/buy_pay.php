<? include handler('template')->file('header'); ?>
<?=ui('loader')->js('@jquery.hook')?>
<div class="m960">
<div class="t_l">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title">订单支付</p>
<div class="sect">
<div class="nleftL" style="font-size:14px; line-height:26px;">
产品名称：<?=$order['product']['flag']?><br/>
订单编号：<span class="B R"><?=$order['orderid']?></span><br/>
应付总额：<span class="B R"><?=$order['paymoney']?></span> 元<br/>
支付方式：<?=$pay['name']?>
</div>
<style type="text/css">
input{cursor:pointer;height:30px;padding:1px 13px;border-color:#999;border-style:solid;border-width:1px; font-size:14px; font-weight:600;}
</style>
<?=$payment_linker?>
<p id="pay_masker" style="display:none;">
<b>
<br/>
请在新打开窗口进行付款操作！
<br/><br/>
如果您已经完成付款，可以 <a href="?mod=me&code=order">点击这里查看订单</a>
</b>
</p>
<div class="sect">
<a href="?mod=buy&code=order&id=<?=$order['orderid']?>">点此选择其他支付方式</a>
</div>
</div>
</div>
</div>
<div class="l_rc_b" ></div>
</div>
<div class="t_r">
<?=ui('widget')->load()?>
</div>
</div>
<script type="text/javascript">
$.hook.add('pay.button.click', function(){
$('#pay_submit').slideUp();
$('#pay_masker').slideDown();
});
</script>
<? include handler('template')->file('footer'); ?>