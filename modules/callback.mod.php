<?php

/**
 * 模块：通知回调接口
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name callback.mod.php
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
		$pid = get('pid');
		$pid || $pid = post('pid');
		$pid || exit($this->Ends());
		preg_match('/^[a-z0-9]+$/i', $pid) || exit($this->Ends());
		
		$payment = logic('pay')->GetOne($pid);
		$payment || exit($this->Ends());
		$status = logic('pay')->Verify($payment);
		$status || exit($this->Ends());
		$parserAPI = logic('callback')->Parser(logic('pay')->TradeData($payment));
		$parserAPI->MasterIframe($this);
		preg_match('/^[a-z_]+$/i', $status) || exit($this->Ends());
		$code = 'Parse_'.$status;
		method_exists($parserAPI, $code) || exit($this->Ends());
		$parserAPI->$code($payment);
	}
	private function Ends()
	{
		echo 'woo.^_^.oow';
	}
}

?>