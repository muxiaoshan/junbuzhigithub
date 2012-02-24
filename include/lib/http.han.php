<?php
/*************************************************************************************************
 * 文件名：http.han.php
 * 版本号：v 1.0
 * 最后修改时间：2005年7月31日 11:11:53
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：对外部提交的变量进行检查的一个类
**************************************************************************************************/
class HttpHandler
{
	function HttpHandler()
	{
		
	}
	
	function &CheckVars(&$array,$reserve=false)
	{
		foreach($array as $key=>$val)
		{
			if($reserve) return ;
			if($key==false) continue;
			if(is_array($val)==false) 
			{
				$array[$key]=HttpHandler::CleanVal($val);
			}
			else 
			{
				$array[$key]=HttpHandler::CheckVars($val);
			}
		}		

		Return $array;
	}
	
	function CleanVal(&$val)
	{
				if(MAGIC_QUOTES_GPC==0) $val = addslashes($val);

				Return $val;
	}
	function UnCleanVal(&$val)
	{
		$val=stripslashes($val);

		return $val;
	}
}

#列子:

?>