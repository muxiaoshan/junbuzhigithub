<div style="margin-left:-30px;">
<div class="field">
<label>手机号</label>
<? if(ini('member.contact.keep')) { ?>
<font class="f_input"><?=user()->get('phone')?></font>
<input type="hidden" name="phone" id="sms_phone" value="<?=user()->get('phone')?>" />
<? } else { ?><input type="text" name="phone" id="sms_phone" value="<?=user()->get('phone')?>" class="f_input" size="30">
<? } ?>
<span class="hint"><? echo $product['type']=='ticket'?'团购券密码将通过短信发到手机上':'团购成功的通知短信会发送到您的手机'; ?><br/>(节假日期间短信稍有延迟，请耐心等待)</span>
</div>
</div>
<script type="text/javascript">
//$(document).ready(function(){
$.hook.add('checkout_submit', function(){
var phone = $('#sms_phone').val();
if (phone == '' || phone == undefined || isNaN(phone) || phone.length != 11)
{
$('#sms_phone').tipTip({
content:"请输入有效的手机号！",
keepAlive:true,
activation:"focus",
defaultPosition:"top",
edgeOffset:8,
maxWidth:"300px"
});
$('#sms_phone').focus();
df_allow_to_submit('sms.inputer', false);
}
else
{
checkout_field_append('phone', phone);
df_allow_to_submit('sms.inputer', true);
}
});
//});
</script>