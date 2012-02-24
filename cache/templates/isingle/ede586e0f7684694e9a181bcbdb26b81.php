<? include handler('template')->file('@admin/header'); ?>
<!-- * 支付宝配置项 * -->
<? $pay = logic('pay')->SrcOne('alipay');
	$cfg = unserialize($pay['config']);
 ?>
<form action="?mod=payment&code=save" method="post"  enctype="multipart/form-data">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
	<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
		<tr class="header">
			<td colspan="2">修改支付宝设置</td>
		</tr>
		<tr>
			<td width="23%" class="td_title">支付宝账户：</td>
			<td width="77%">
				<input name="cfg[account]" type="text" value="<?=$cfg['account']?>">
				* ( <a href="<?=ihelper('tg.payment.alipay')?>" target="_blank"><font color="red">天天团购专属通道：点此免费开通支付宝</font></a> )
			</td>
		</tr>
		<tr>
			<td class="td_title">交易安全校验码：</td>
			<td>
				<input name="cfg[key]" type="text" size="35" value="<?=$cfg['key']?>" />
			</td>
		</tr>
		<tr>
			<td class="td_title">合作者身份ID：</td>
			<td>
				<input name="cfg[partner]" type="text" value="<?=$cfg['partner']?>" />
			</td>
		</tr>
<? $ssl = function_exists('openssl_open') ?>
<tr>
			<td class="td_title">支付接口类型：</td>
			<td>
				<select name="cfg[service]">
					<option value="create_direct_pay_by_user"
<? if($cfg['service']=='create_direct_pay_by_user') { ?>
 selected="selected"
<? } ?>
>即时到帐接口</option>
<? if($ssl) { ?>
					<option value="create_partner_trade_by_buyer"
<? if($cfg['service']=='create_partner_trade_by_buyer') { ?>
 selected="selected"
<? } ?>
>担保交易接口</option>
					<option value="trade_create_by_buyer"
<? if($cfg['service']=='trade_create_by_buyer') { ?>
 selected="selected"
<? } ?>
>支付宝双接口</option>
					
<? } ?>
</select>
<? if(!$ssl) { ?>
* 您的空间不支持OpenSSL，不能使用“担保交易接口”和“支付宝双接口”
<? } ?>
</td>
		</tr>
		<tr>
			<td class="td_title">是否使用SSL连接验证：</td>
			<td>
				<select name="cfg[ssl]">
<? if($ssl) { ?>
<option value="true"
<? if($cfg['ssl']=='true') { ?>
 selected="selected"
<? } ?>
>是</option>
<? } ?>
<option value="false"
<? if($cfg['ssl']=='false') { ?>
 selected="selected"
<? } ?>
>否</option>
				</select>
				 <br/>*注1：SSL是一种安全的HTTP连接，选择“是”的话，则您的空间必须支持OpenSSL才可以
<? if($ssl) { ?>
（您的空间支持OpenSSL）
<? } else { ?><b>（您的空间不支持OpenSSL，只能选择“否”）</b>
<? } ?>
 <br/>*注2：<font color="red">只有即时到帐接口可以选择“否”，其他接口必须选择“是”，且空间必须支持OpenSSL，否则交易会失败！</font>
				 <br/>*注3：<b>国外空间使用“担保交易接口/支付宝双接口”时有可能会造成无法发货的问题，请尽量选择使用国内空间！</b>
			</td>
		</tr>
		<tr>
			<td class="tr_nav tr_center" colspan="2">
				支付宝大快捷 - <a href="<?=ihelper('tg.payment.alipay.fpm')?>" target="_blank"><font color="blue">点此申请</font></a>
			</td>
		</tr>
		<tr class="tips">
			<td class="tr_center" colspan="2">
				<font color="red">提醒用户：使用大快捷应用之前请先确认申请开通了支付宝相应权限</font>
			</td>
		</tr>
		<tr>
			<td class="td_title">是否开启快捷登录？</td>
			<td><font class="ini" src="alipay.account.login.enabled"></font></td>
		</tr>
		<tr>
			<td class="td_title">是否开启快速获取收货地址？</td>
			<td><font class="ini" src="alipay.address.import.enabled"></font></td>
		</tr>
	</table>
	<br/>
	<center>
		<input type="hidden" name="id" value="<?=$pay['id']?>" />
		<input type="submit" name="submit" value="提 交" class="button" />
	</center>
</form>
<?=ui('loader')->js('#admin/js/sdb.parser')?>
<? include handler('template')->file('@admin/footer'); ?>