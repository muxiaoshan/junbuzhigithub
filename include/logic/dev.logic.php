<?php

/**
 * 逻辑区：开发管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name dev.logic.php
 * @version 1.1
 */

class DevelopmentLogic
{
	public function debugSwitch($do, $class, $data = null)
	{
		$file = $class == 'index' ? 'index.php' : 'admin.php';
		$file = INCLUDE_PATH.'load/'.$file;
		if ($do == 'get')
		{
			return $this->debugSwitch_get($file);
		}
		else
		{
			$debug = $data == 'true' ? 'true' : 'false';
			return $this->debugSwitch_set($file, $debug);
		}
	}
	private function debugSwitch_get($file)
	{
		$code = file_get_contents($file);
		preg_match('/define\(\'debug\',\s*([true|false]+)\);/i', $code, $mch);
		$debug = strtolower($mch[1]);
		return $debug == 'true' ? true : false;
	}
	private function debugSwitch_set($file, $data)
	{
		$code = file_get_contents($file);
		$code = preg_replace('/(define\(\'debug\',\s*)([true|false]+)(\);)/i', '${1}'.$data.'${3}', $code);
		return file_put_contents($file, $code);
	}
}

?>