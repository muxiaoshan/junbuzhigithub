<?php

/**
 * 模块：商家后台
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name seller.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	private $uid = 0;
	private $sid = 0;
	
	private function iniz()
	{
		$this->uid = user()->get('id');
		if ($this->uid < 0)
		{
			$this->Messager('请先登录！', '?mod=account&code=login');
		}
		$this->sid = logic('seller')->U2SID($this->uid);
		if ($this->sid < 0)
		{
			$this->Messager('管理员还没有添加您的商家信息，您暂时无法查看商家后台！', 0);
		}
	}
	function ModuleObject( $config )
	{
		$this->MasterObject($config);
		$this->iniz();
		$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	function main()
	{
		header('Location: '.rewrite('?mod=seller&code=product&op=list'));
	}
	function product_list()
	{
		$products = logic('product')->GetList(-1, null, 'p.sellerid='.$this->sid);
		logic('seller')->AVParser($products);
		include handler('template')->file('seller_product_list');
	}
	function product_ticket()
	{
		$pid = get('pid', 'int');
				$status = get('status')!==false ? get('status', 'int') : TICK_STA_ANY;
		$fLinkBase = rewrite('?mod=seller&code=product&op=ticket&pid='.$pid.'&status=');
		$fLink = array(
			'all' => array('link'=>$fLinkBase.TICK_STA_ANY,'current'=>''),
			'used' => array('link'=>$fLinkBase.TICK_STA_Used,'current'=>''),
			'unused' => array('link'=>$fLinkBase.TICK_STA_Unused,'current'=>'')
		);
		abs($status) > 1 && $status = TICK_STA_ANY;
		$status == TICK_STA_ANY && $fLink['all']['current'] = ' class="filter_current"';
		$status == TICK_STA_Used && $fLink['used']['current'] = ' class="filter_current"';
		$status == TICK_STA_Unused && $fLink['unused']['current'] = ' class="filter_current"';
				$product = logic('product')->SrcOne($pid);
		$tickets = logic('coupon')->GetList(USR_ANY, ORD_ID_ANY, $status, $pid);
		include handler('template')->file('seller_product_ticket');
	}
	function ajax_alert()
	{
		$id = get('id', 'int');
		$c = logic('coupon')->GetOne($id);
		logic('notify')->Call($c['uid'], 'admin.mod.coupon.Alert', $c);
		exit('ok');
	}
}

?>