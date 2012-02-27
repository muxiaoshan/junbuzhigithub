<? include handler('template')->file('header'); ?>
<div class="m960">
<div class="t_l">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title">团购券查询</p>
<div class="sect">
<form action="<?=$action?>" method="post"  enctype="multipart/form-data">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<div id="ck_product_name"></div>
<div class="nleftL">
<div class="field">
<label>团购券编号：</label>
<input type="text" id="number" name="number" value="" class="f_input"  size="30">
<span id="status" class="hint">请输入团购券编号</span>
</div>
<div class="field">
<label>团购券密码：</label>
<input type="text" id="password" name="password" class="f_input"  size="30">
</div>
<div class="clear"></div>
<div class="act">
<input type="button" id="btn_tickCheck" class="formbutton formbutton_invite" name='submit'  value="查询">
&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" id="btn_tickUse" class="formbutton left10 formbutton_invite" name='submit'  value="消费">
</div>
</div>
</form>
</div>
</div>
</div>
<div class="l_rc_b" ></div>
</div> 
<script type="text/javascript">
var ms_url = '<?=$action?>';
function tickCheck()
{
var num = $('#number').val();
if (num.length != 12 && num.length != 16)
{
status('<font color="red">请输入正确的团购券编号！</font>');
return;
}
status('正在查询中...');
$.get(ms_url+'&do=check&number='+$('#number').val()+'&timer='+(new Date()).getTime(), function(data){
status(data);
});
}
function tickMakeUse()
{
var num = $('#number').val();
if (num.length != 12 && num.length != 16)
{
status('<font color="red">请输入正确的团购券编号！</font>');
return;
}
var password = $('#password').val();
if (password.length != 6)
{
status('<font color="red">请输入正确的团购券密码！</font>');
return;
}
if (!confirm('您确认要消费此团购券吗？')) return;
status('正在登记中...');
$.get(ms_url+'&do=makeuse&number='+$('#number').val()+'&password='+$('#password').val()+'&timer='+(new Date()).getTime(), function(data){
status(data);
});
}
function tickGetProductName()
{
var num = $('#number').val();
if (num.length != 12 && num.length != 16)
{
return;
}
$('#ck_product_name').html('正在查询产品名称...');
$.get(ms_url+'&do=getname&number='+num+'&timer='+(new Date()).getTime(), function(data){
$('#ck_product_name').hide();
$('#ck_product_name').html(data);
PNDiv_Position_Show();
});
}
function status(html, color)
{
$('#status').html(html);
}
$(document).ready(function(){
//$('#btn_tickCheck').bind('click', function(){tickCheck()});
$('#btn_tickUse').bind('click', function(){tickMakeUse()});
$('#number').bind('blur', function(){tickGetProductName();tickCheck();});
});
function PNDiv_Position_Show()
{
var iH = $('#ck_product_name').outerHeight();
var iT = $('#number').position().top-iH-10+'px';
var iL = $('#number').position().left+'px';
$('#ck_product_name').css('top', iT);
$('#ck_product_name').css('left', iL);
$('#ck_product_name').slideDown();
}
</script>
<div class="t_r">
<?=ui('widget')->load()?>
</div>
</div>
<? include handler('template')->file('footer'); ?>