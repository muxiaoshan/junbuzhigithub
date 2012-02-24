<?php

/**
 * 模块：帐户充值
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name recharge.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		if (MEMBER_ID < 1)
		{
			$this->Messager(__('请先登录！'), '?mod=account&code=login');
		}
		$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	public function main()
	{
		include handler('template')->file('recharge');
	}
	public function order()
	{
		$id = $this->__orderid();
		$order = logic('recharge')->GetOne($id);
		if (!$order)
		{
			$this->Messager('订单编号无效！', -1);
		}
		include handler('template')->file('recharge_order');
	}
	public function order_save()
	{
		$money = round((float)post('money'), 2);
		if (!$money || $money <= 0)
		{
			$this->Messager('充值金额无效！', -1);
		}
		$order = logic('recharge')->GetFree($money);
		header('Location: '.rewrite('?mod=recharge&code=order&id='.$order['orderid']));
	}
	public function payment_save()
	{
		$id = $this->__orderid();
		$pid = post('payment_id', 'int');
		$test = logic('recharge')->GetOne($id);
		if (!$test)
		{
			$this->Messager('订单编号无效！', -1);
		}
		logic('recharge')->Update($id, array('payment'=>$pid));
		header('Location: '.rewrite('?mod=recharge&code=pay&id='.$id));
	}
	public function card_redirect()
	{
		$payment = logic('pay')->GetOne('recharge');
		if ($payment['enabled'] != 'true')
		{
			$this->Messager('本站没有开放充值卡功能，请返回使用其他支付方式！', -1);
		}
		$order = logic('recharge')->GetFree(0);
		logic('recharge')->Update($order['orderid'], array('payment'=>$payment['id']));
		header('Location: '.rewrite('?mod=recharge&code=pay&id='.$order['orderid']));
	}
	public function pay()
	{
		$id = $this->__orderid();
		$order = logic('recharge')->GetOne($id);
		if (!$order)
		{
			$this->Messager('订单编号无效！', -1);
		}
		if ($order['payment'] == 0)
		{
			$this->Messager('请选择充值方式！', '?mod=recharge&code=order&id='.$id);
		}
		if ($order['paytime'] > 0 || $order['status'] != RECHARGE_STA_Blank)
		{
			$this->Messager('此订单已经充值过了！', '?mod=me&code=bill');
		}
		$pay = logic('pay')->GetOne($order['payment']);
		if (!$pay)
		{
			$this->Messager('无效的充值方式！', -1);
		}
		$parameter = array(
			'name' => '账户充值（'.$id.'）',
			'detail' => '充值：'.$order['money'].'元，充值编号：'.$id,
			'price' => $order['money'],
			'sign' => $order['orderid'],
			'notify_url' => ini('settings.site_url').'/index.php?mod=callback&pid='.$pay['id'],
			'product_url' => ini('settings.site_url').'/index.php?mod=me&code=bill'
		);
		$payment_linker = logic('pay')->Linker($pay, $parameter);
		include handler('template')->file('recharge_pay');
	}
	private function __orderid()
	{
		$id = get('id', 'number');
		if (!$id || strlen($id) != 13)
		{
			$this->Messager('请输入正确的订单编号！', -1);
		}
		return $id;
	}
	
}

?>
