<? include handler('template')->file('@admin/header'); ?>
<?=ui('loader')->js('#admin/js/sdb.parser')?>
<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="6">短信服务器通道列表</td> </tr> <tr class="banner"> <td colspan="6">
是否开启自动重发功能？
<font class="ini" src="service.sms.autoERSend"></font>
[ 开启后系统会自动检测短信中的敏感词，自动用空格间隔开并重发 ]
</td> </tr> <tr class="tr_nav"> <td width="5%" bgcolor="#F4F8FC">编号</td> <td width="12%" bgcolor="#F4F8FC">名称</td> <td width="30%" bgcolor="#F4F8FC">说明</td> <td width="16%" bgcolor="#F4F8FC">使用次数 <a href="#help" onclick="show_counter_help();return false;" style="color:red;">#说明#</a></td> <td width="16%" bgcolor="#F4F8FC">启用状态</td> <td width="12%" align="center" bgcolor="#F4F8FC">管理</td> </tr> 
<? if(is_array($list)) { foreach($list as $one) { ?>
 <tr> <td><?=$one['id']?></td> <td><?=$one['name']?></td> <td><?=$drivers[$one['flag']]['intro']?></td> <td><?=$one['count']?></td> <td> <font class="dbf" src="id:<?=$one['id']?>@service/enabled"><?=$one['enabled']?></font>
<? if($one['enabled'] == 'true') { ?>
<font class="status" title="<?=$one['id']?>"></font>
<? } ?>
</td> <td align="center">
[ <a href="?mod=service&code=sms&op=edit&id=<?=$one['id']?>">设置</a> ]
<br/>
[ <a href="index.php?mod=apiz&code=sms&op=ServerTest&flag=<?=$one['flag']?>">通道检测</a> ]
</td> </tr> 
<? } } ?>
 <tr class="tips"> <td colspan="6">
提示：开启多个通道的时候系统会自动随机选择一条通道进行发送！（<font color="red">订阅群发时建议关闭电信通道</font>）
</td> </tr> <tr> <td colspan="6">
输入手机号码：<input id="phone" type="text" />
输入短信内容：<input id="content" type="text" /> <input type="button" value="发送测试短信" onclick="javascript:smsTest();" class="is_submit_button" /> </td> </tr> <tr class="banner"> <td colspan="6"><b>短信剩余量预警</b> <a href="#setting_toggle" onclick="$('#sms_w_config').toggle();return false;">:: 设置 ::</a></td> </tr> <tr id="sms_w_config" style="display: none;"> <td colspan="6">
<? $list = array_merge(array(array('id' => 0, 'name' => '--停用预警--')), $list); ?>
针对 <select id="smsw_driver">
<? if(is_array($list)) { foreach($list as $one) { ?>
<option value="<?=$one['id']?>" 
<? if($one['id'] == (int)$smsw['serviceID']) { ?>
 selected="selected"
<? } ?>
><?=$one['name']?></option>
<? } } ?>
</select>
每隔 <input id="smsw_interval" type="text" value="<?=$smsw['interval']?>" size="1" /> 小时自动检查一次
当短信剩余不足 <input id="smsw_surplus" type="text" value="<?=$smsw['surplus']?>" size="2" /> 条时
发送报警短信到手机 <input id="smsw_phone" type="text" value="<?=$smsw['phone']?>" /> <input id="smsw_save" type="button" class="wbutton" value="保存设置" /> <input id="smsw_test" type="button" class="wbutton" value="发送测试" /> </td> </tr> </table> <a href="?mod=push&code=log&type=sms" class="back1 back2">短信日志 </a> <script type="text/javascript">
function smsTest()
{
window.location = '?mod=service&code=sms&op=test&phone='+document.getElementById('phone').value+'&content='+encodeURIComponent(document.getElementById('content').value);
}
$(document).ready(function(){
$('#smsw_save').bind('click', smsw_save);
$('#smsw_test').bind('click', smsw_test); 
});
function smsw_save()
{
$.notify.loading('正在保存...');
var arg = '';
arg += '&driver='+$('#smsw_driver').val();
arg += '&interval='+$('#smsw_interval').val();
arg += '&surplus='+$('#smsw_surplus').val();
arg += '&phone='+$('#smsw_phone').val();
$.get('?mod=service&code=smsw&op=save'+arg+$.rnd.stamp(), function(data){
$.notify.loading(false);
if (data == 'ok')
{
$('#sms_w_config').hide();
$.notify.success('保存成功！');
}
else
{
$.notify.failed('保存失败！<hr/>'+data);
}
});
}
function smsw_test()
{
if (!confirm('如果您修改了设置，进行测试前请先确认您已经保存！')) return;
$.notify.loading('正在发送测试短信...');
$.get('?mod=service&code=smsw&op=test'+$.rnd.stamp(), function(data){
$.notify.loading(false);
if (data == 'ok')
{
$.notify.success('发送成功！');
}
else
{
$.notify.failed('发送失败！<hr/>'+data);
}
});
}
function show_counter_help()
{
art.dialog({
title: '帮助手册',
icon: 'question',
lock: true,
content: '这里的“使用次数”仅仅是服务接口的调用次数，<b style="color:red;">并不是您实际的短信使用量！</b>',
yesText: '知道了',
yesFn: true
});
}
</script>
<? include handler('template')->file('@admin/footer'); ?>