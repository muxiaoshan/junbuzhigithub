<?php

/**
 * 模块：支付方式管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name payment.mod.php
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
		header('Location: ?mod=payment&code=vlist');
	}
	public function vList()
	{
				$list = logic('pay')->SrcList();
				$list_local = $this->local_list();
		include handler('template')->file('@admin/payment_list');
	}
	public function install()
	{
		$flag = get('flag', 'txt');
		$list_local = $this->local_list();
		if (!isset($list_local[$flag]))
		{
			$this->Messager('不可识别的支付标记，系统无法进行安装！', '?mod=payment&code=vlist');
		}
				$db_pay = logic('pay')->SrcOne($flag);
		if ($db_pay['id'])
		{
			$this->Messager('支付方式已经安装过了！', '?mod=payment&code=vlist');
		}
		$payment = $list_local[$flag];
				$r = dbc(DBCMax)->insert('payment')->data(array('code' => $flag, 'name' => $payment['name'], 'detail' => $payment['detail'], 'order' => 888, 'config' => 'N;', 'enabled' => 'false'))->done();
		if ($r)
		{
			$this->Messager('安装成功！', '?mod=payment&code=vlist');
		}
		else
		{
			$this->Messager('安装失败！', '?mod=payment&code=vlist');
		}
	}
	function Config()
	{
		$flag = get('flag', 'txt');
		$file = DRIVER_PATH.'payment/'.$flag.'.config.php';
		if (!is_file($file))
		{
			$this->Messager('此支付方式没有配置项！');
		}
		else
		{
			include handler('template')->absfile($file);
		}
	}
	private function Config_link($flag)
	{
		$file = DRIVER_PATH.'payment/'.$flag.'.config.php';
		if (!is_file($file))
		{
			return '<font title="此接口不需要设置">设置</font>';
		}
		else
		{
			return '<a href="?mod=payment&code=config&flag='.$flag.'">设置</a>';
		}
	}
	function Save()
	{
				if ($_POST['cfg']['content'] && post('replacer') == 'true')
		{
			$_POST['cfg']['content'] = str_replace(array('"','\\',"'"), '', $_POST['cfg']['content']);
		}
				$data = array(
			'config' => serialize(post('cfg', 'trim'))
		);
		logic('pay')->Update($data, 'id='.post('id', 'number'));
		$this->Messager('修改完成！', '?mod=payment');
	}
	private $payment_local_list = null;
	private function local_list()
	{
		if (is_null($this->payment_local_list))
		{
			$list_local = array();
			$local_file = DRIVER_PATH.'payment/payment.list.php';
			if (is_file($local_file))
			{
				$list_local = include $local_file;
			}
			$this->payment_local_list = $list_local;
		}
		return $this->payment_local_list;
	}
}

?>