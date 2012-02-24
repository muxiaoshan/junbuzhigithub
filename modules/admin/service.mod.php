<?php

/**
 * 模块：服务器列表管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name service.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	function Main()
	{
		$list = array(
			'mail' => '邮件服务器',
			'sms' => '短信服务器'
		);
		include handler('template')->file('@admin/service');
	}
	
	function Mail()
	{
		$list = logic('service')->mail()->GetList();
		$balance = ini('service.mail.balance');
		include handler('template')->file('@admin/service_mail_list');
	}
	function Mail_balance()
	{
		$power = get('power', 'txt');
		$tf = ($power == 'on') ? true : false;
		ini('service.mail.balance', $tf);
		$this->Messager('更新完成！');
	}
	function Mail_add()
	{
		$actionName = '添加';
		include handler('template')->file('@admin/service_mail_mgr');
	}
	function Mail_edit()
	{
		$actionName = '编辑';
		$id = get('id', 'int');
		if (!$id)
		{
			$this->Messager('非法编号！');
		}
		$c = logic('service')->mail()->GetOne($id);
		include handler('template')->file('@admin/service_mail_mgr');
	}
	function Mail_save()
	{
		$id = post('id', 'int');
		$c = array();
		$c['flag'] = post('flag', 'txt');
		$c['name'] = post('name', 'txt');
		$c['weight'] = post('weight', 'int');
		$c['enabled'] = post('enabled', 'txt');
		$c['config'] = serialize(post('cfg', 'trim'));
		logic('service')->mail()->Update($id, $c);
		$this->Messager('更新成功！', '?mod=service&code=mail');
	}
	function Mail_switch()
	{
		$id = get('id', 'int');
		if (!$id)
		{
			$this->Messager('非法编号！');
		}
		$power = get('power', 'txt');
		$data['enabled'] = ($power == 'on') ? 'true' : 'false';
		logic('service')->mail()->Update($id, $data);
		$this->Messager('更新成功！');
	}
	function Mail_del()
	{
		$id = get('id', 'int');
		if (!$id)
		{
			$this->Messager('非法编号！');
		}
		logic('service')->mail()->Del($id);
		$this->Messager('删除成功！');
	}
	function Mail_test()
	{
		$mail = get('mail', 'txt');
		$info = logic('service')->mail()->Test($mail);
		include handler('template')->file('@admin/service_mail_test');
	}
	
	function SMS()
	{
		$list = logic('service')->sms()->GetList();
		$drivers = logic('service')->sms()->DriverList();
		$smsw = app('smsw')->config();
		include handler('template')->file('@admin/service_sms_list');
	}
	function SMS_add()
	{
		$actionName = '添加';
		$drivers = logic('service')->sms()->DriverList();
		include handler('template')->file('@admin/service_sms_mgr');
	}
	function SMS_edit()
	{
		$id = get('id', 'int');
		if (!$id)
		{
			$this->Messager('非法编号！');
		}
		$c = logic('service')->sms()->GetOne($id);
		$actionName = '编辑';
		$drivers = logic('service')->sms()->DriverList();
		include handler('template')->file('@admin/service_sms_mgr');
	}
	function SMS_save()
	{
		$id = post('id', 'int');
		$c = array();
								$c['enabled'] = post('enabled', 'txt');
		$c['config'] = serialize(post('cfg', 'trim'));
		logic('service')->sms()->Update($id, $c);
		$this->Messager('更新成功！', '?mod=service&code=sms');
	}
	function SMS_switch()
	{
		$id = get('id', 'int');
		if (!$id)
		{
			$this->Messager('非法编号！');
		}
		$power = get('power', 'txt');
		$data['enabled'] = ($power == 'on') ? 'true' : 'false';
		logic('service')->sms()->Update($id, $data);
		$this->Messager('更新成功！');
	}
	function SMS_del()
	{
		$id = get('id', 'int');
		if (!$id)
		{
			$this->Messager('非法编号！');
		}
		logic('service')->sms()->Del($id);
		$this->Messager('删除成功！');
	}
	function SMS_test()
	{
		$phone = get('phone', 'txt');
		$content = get('content');
		$info = logic('service')->sms()->Test($phone, $content);
		include handler('template')->file('@admin/service_sms_test');
	}
	function SMS_status()
	{
		$id = get('id', 'int');
		echo logic('service')->sms()->Status($id);
		exit;
	}
	
	function SMSW_save()
	{
		$cfg = array(
			'serviceID' => get('driver', 'int'),
			'interval' => get('interval', 'int'),
			'surplus' => get('surplus', 'int'),
			'phone' => get('phone', 'number')
		);
		exit(app('smsw')->config($cfg) ? 'ok' : 'failed');
	}
	function SMSW_test()
	{
		exit((string)app('smsw')->test_send());
	}
}

?>