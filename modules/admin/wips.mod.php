<?php

/**
 * 模块：基于WEB的入侵检测系统
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name wips.mod.php
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
	function main()
	{
		include handler('template')->file('@admin/wips_index');
	}
	function sql()
	{
		include handler('template')->file('@admin/wips_sql_config');
	}
	function sql_save()
	{
				ini('wips.sql.dfunction', post('dfunction'));
		ini('wips.sql.daction', post('daction'));
		ini('wips.sql.dnote', post('dnote'));
		ini('wips.sql.afullnote', post('afullnote'));
		ini('wips.sql.dlikehex', post('dlikehex'));
				$this->Messager('保存成功！', '?mod=wips');
	}
}

?>