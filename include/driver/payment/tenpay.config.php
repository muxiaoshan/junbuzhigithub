<!--{template @admin/header}-->

<!-- * 财付通配置项 * -->

{eval
    $pay = logic('pay')->SrcOne('tenpay');
    $cfg = unserialize($pay['config']);
}

<style type="text/css">
.dsp_for_medi {display: none;}
</style>

<form action="?mod=payment&code=save" method="post" enctype="multipart/form-data">
	<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
		<tr class="header">
			<td colspan="2">修改财付通设置</td>
		</tr>
		<tr>
			<td class="td_title" width="20%">财付通密钥：</td>
			<td>
				<input name="cfg[key]" type="text" size="35" value="{$cfg['key']}" />
			</td>
		</tr>
        <tr>
            <td class="td_title">支付接口类型：</td>
            <td>
                <select id="cfg_service" name="cfg[service]" onchange="dspChange()">
                    <option value="direct"{echo ($cfg['service']=='direct'||$cfg['service']=='')?' selected="selected"':''}>即时到帐接口</option>
                    <option value="medi"{echo $cfg['service']=='medi'?' selected="selected"':''}>担保交易接口</option>
                </select>
            </td>
        </tr>
		<tr class="dsp_for_direct">
			<td class="td_title">财付通商户号：</td>
			<td>
				<input name="cfg[bargainor]" type="text" value="{$cfg['bargainor']}">
			</td>
		</tr>
        <tr class="dsp_for_direct">
            <td class="td_title">支付接口版本：</td>
            <td>
                <select name="cfg[version]">
                    <option value="3"{echo ($cfg['version']=='3'||$cfg['version']=='')?' selected="selected"':''}>v 3.0</option>
                    <option value="1"{echo $cfg['version']=='1'?' selected="selected"':''}>v 1.0</option>
                </select>
                * 如果3.0版本使用有问题，请切换至1.0版本
            </td>
        </tr>
        <tr class="dsp_for_medi">
            <td colspan="2" style="color: red;">
                注意：因为财付通担保交易接口不支持自动发货，建议不要开启！<b>推荐使用支付宝担保交易接口！</b>
            </td>
        </tr>
        <tr class="dsp_for_medi">
            <td class="td_title">平台商帐号：</td>
            <td>
                <input name="cfg[chnid]" type="text" value="{$cfg['chnid']}" />
            </td>
        </tr>
        <tr class="dsp_for_medi">
            <td class="td_title">卖家财付通帐号：</td>
            <td>
                <input name="cfg[seller]" type="text" value="{$cfg['seller']}" />
            </td>
        </tr>
	</table>
	<br/>
	<center>
		<input type="hidden" name="id" value="{$pay['id']}" />
		<input type="submit" name="submit" value="提 交" class="button" />
	</center>
</form>
<script type="text/javascript">
function dspChange()
{
    var ser = $('#cfg_service').val();
    if (ser == 'direct')
    {
        $('.dsp_for_medi').hide();
        $('.dsp_for_direct').show();
    }
    else
    {
        $('.dsp_for_direct').hide();
        $('.dsp_for_medi').show();
    }
}
$(document).ready(function(){
   dspChange(); 
});
</script>
<!--{template @admin/footer}-->