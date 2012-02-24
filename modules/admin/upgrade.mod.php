<?php

/**
 * 模块：升级控制
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name upgrade.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	var $server="";
	
	private static $err_noData_help = '';
	private static $err_Format_help = '';
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		include_once(LIB_PATH.'io.han.php');
self::$err_noData_help = <<<HELP
抱歉，当前无法连接到升级服务器！<br/>
--------------------------<br/>
<font id="update_server_test">正在重新检查...</font>
<br/>
--------------------------<br/>
如果有疑问，您可以联系在线客服QQ：<a href="#" onclick="javascript:window.open('http://bizapp.qq.com/webc.htm?new=0&sid=800058566&o=tttuangou.net&q=7', '_blank', 'height=544, width=644,toolbar=no,scrollbars=no,menubar=no,status=no');return false;">800058566</a><br/>
（需要提供相应服务器权限）
<script type="text/javascript">jQuery(document).ready(function(){jQuery.get('index.php?mod=apiz&code=update&op=ServerTest'+jQuery.rnd.stamp(), function(data){jQuery('#update_server_test').html(data)})});</script>
HELP;
$helpLinker = ihelper('tg.upgrade.error.format');
self::$err_Format_help = <<<HELP
抱歉，当前升级人数太多，请稍候进行尝试！&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$helpLinker}" target="_blank">查看帮助</a>
HELP;
		Load::moduleCode($this);$this->Execute();
	}
	function Execute()
	{
		switch($this->Code)
		{
			case 'check':
				$this->check();
				break;
			case 'download':
				$this->download();
				break;
			case 'install':
				$this->install();
				break;
			case 'signup':
				 $this->Signup();
				 break;
			case 'clear_cache':
				$this->clearCache();
			case 'inizd':
				$this->envCheck();
			case 'manual':
				$this->upsManual();
			default:
				$this->Main();
				break;
		}
	}
	function Main()
	{
		$this->Messager('<div style="text-align:left;">系统即将进入自动升级模式 ....<hr style="border:1px dashed #ccc;" />如果您的空间无法在线升级，请 <a href="?mod=upgrade&code=manual">点击此处</a> 进入 <b>手动升级</b> 模式！</a></div>', '?mod=upgrade&code=inizd', 5);
	}
	function envCheck()
	{
				$dir_list=array("api","app","backup","cache","data","uploads","static","errorlog","include","modules","setting","templates","./",);
		foreach ($dir_list as $dir)
		{
			$path=ROOT_PATH.$dir;
			if(is_writable($path)==false)$this->Messager("{$path}目录不可写，请将其属性改成0777", null);
		}
				if(!function_exists("gzopen"))$this->Messager("您的服务器不支持gzopen函数，不能执行升级。", null);
		if(!function_exists("md5_file"))$this->Messager("您的服务器不支持md5_file函数，不能执行升级。", null);
				$_free_space_src = diskfreespace('.');
		if (is_null($_free_space_src) || $_free_space_src <= 0)
		{
			$this->Messager('无法检查磁盘剩余空间！升级前请先确认剩余空间充足（大于10M）<br/><b>否则极有可能导致升级失败！</b><br/><br/>（继续升级请 <a href="admin.php?mod=upgrade&code=check">点击此处</a>）', null);
		}
		else
		{
			$_free_space = intval($_free_space_src / (1024 * 1024));
			if ($_free_space < 10)
			{
				$this->Messager('磁盘剩余空间太小（不足10M），无法升级！', null);
			}
		}
				$this->Messager("正在检查新版本...", "admin.php?mod=upgrade&code=check");
	}
	function Signup()
	{
		$this->OPC == 'request' && $this->Signup_request();
		$this->checkResponse('acl.denied');
	}
	function Signup_request()
	{
		$account = post('account');
		$password = post('password');
		$result = logic('acl')->Signup($account, $password);
		if ($result != 'ok')
		{
			$this->checkErrorNoDATA($result);
			$this->Messager($result, -1);
		}
		$aclData = logic('acl')->Account();
		$uStop = $aclData['upgrade']['stop'];
		if ($uStop)
		{
			$this->Messager($uStop, null);
		}
				clearcache();
				header('Location: admin.php?mod=upgrade');
	}
	private function checkErrorNoDATA($response)
	{
		if ($response == 'error_nodata')
		{
			$this->Messager(self::$err_noData_help, null);
		}
		if ($response == 'error_format')
		{
			$this->Messager(self::$err_Format_help, null);
		}
	}
	private function checkResponse($response)
	{
		if (!is_string($response)) return $response;
		if (logic('acl')->RPSFailed($response))
		{
			include handler('template')->file('@admin/upgrade_acl_signup');
			exit;
		}
		$this->checkErrorNoDATA($response);
		return $response;
	}
	
	
	function check()
	{
		@unlink(CACHE_PATH.'upgrade.lock'); 		$response = $this->checkResponse(request('upgrade', array(), $error));
		if ($response == SYS_VERSION)
		{
			$this->Messager('您使用的已是最新版本，无需升级', null);
		}
		elseif (is_string($response))
		{
			$this->Messager('获取版本信息时出错，请重试！('.$response.')', null);
		}
		else
		{
			$next_url = 'admin.php?mod=upgrade&code=download&version='.$response['version'].'&build='.$response['build'].'&file='.$response['file'].'&size='.$response['file_size'].'&hash='.$response['file_hash'].'&start=1';
			include handler('template')->file('@admin/upgrade_change_log');
			exit;
		}
	}
	
	function download()
	{
		$file=$this->Post['file']?$this->Post['file']:$this->Get['file'];
		$size=$this->Post['size']?$this->Post['size']:$this->Get['size'];
		$hash=$this->Post['hash']?$this->Post['hash']:$this->Get['hash'];
		$version=$this->Post['version']?$this->Post['version']:$this->Get['version'];
		$build = $this->Post['build']?$this->Post['build']:$this->Get['build'];
		$quick = $this->Post['quick']?$this->Post['quick']:$this->Get['quick'];
		
		if (!$file || !$size || !$hash || !$version || !$build)$this->Messager("参数错误",null);

		$url="admin.php?mod=upgrade&code=download&version={$version}&build={$build}&file={$file}&size={$size}&hash={$hash}&quick={$quick}";
				if($this->Get['start'])
		{
			$this->Messager("正在启用下载进程...",$url,0);
		}
		
		$upgrade_data_dir = DATA_PATH.'upgrade/';
		is_dir($upgrade_data_dir) || @mkdir($upgrade_data_dir, 0777);
		$tmp_file = $upgrade_data_dir.SYS_VERSION.'~'.$version.".zip";
		$tmp_exists=is_file($tmp_file);
		if($tmp_exists)$tmp_md5=md5_file($tmp_file);
		$offset=$tmp_exists?@filesize($tmp_file):0;
		
				if($offset>=$size && $tmp_md5!=$hash)
		{
			@unlink($tmp_file);
			$this->Messager(null,$url);
		}
		
		if($offset==$size && $tmp_md5==$hash)
		{
			$this->Messager("升级包已经成功下载,正在开始升级...","admin.php?mod=upgrade&code=install&step=check&version={$version}&quick={$quick}",0);
		}
		
				$length=rand(102400,102400*2);
		$request=array('version'=>$version,'build'=>$build,'file'=>$file,'hash'=>$hash,'offset'=>$offset,'length'=>$length);
		$data=$this->checkResponse(request('download', $request, $error));
		if($error) $this->Messager($data,null);
		
				$md5=$data['hash'];
		$data=$data['upgrade_data'];
		if ($md5!=md5($data)) {
			@unlink($tmp_file);
			$this->Messager("程序传输过程中数据出错，请重新升级。",null);
		}
		
		if(!$data && $tmp_exists==false)$this->Messager("请求失败，请稍候在试。",null);
		
				$fp=fopen($tmp_file,$tmp_exists?"ab":"wb");
		if($fp==false)$this->Messager($tmp_file."文件无法写入");
		$write_length=fwrite($fp,$data,$length);
		fclose($fp);
		$percent=(number_format($offset/$size,2)*100)."%";
		$this->Messager("正在下载升级包，已下载{$percent}",$url,0);

	}
	
	function install()
	{
		@set_time_limit(120);
		$version=$this->Post['version']?$this->Post['version']:$this->Get['version'];
		$step=$this->Get['step'];
		$status=(int)$this->Get['status'];		if(empty($version))$this->Messager("参数错误");
		$odver = get('odver') ? get('odver') : SYS_VERSION;
		$url="admin.php?mod=upgrade&code=install&version=$version&odver=$odver";
				$upgrade_data_dir = DATA_PATH.'upgrade/';
		$upcName = $odver.'~'.$version;
		$upgrade_file = $upgrade_data_dir.$upcName.".zip";
		if (is_file($upgrade_file)==false)
		{
			$this->Messager("升级包已经不存在，请重新下载", null);
		}
		$upgrade_tmp_dir = $upgrade_data_dir.$odver.'~'.$version.'/';
		is_dir($upgrade_tmp_dir) || @mkdir($upgrade_tmp_dir, 0777);
		
		include_once(LIB_PATH.'io.han.php');
		
				if($step=='check')
		{
			$quick = $this->Get['quick'];
			$check_url=$url."&step=check&quick={$quick}";
			if($status===0) $this->Messager("正在释放临时文件...",$check_url.'&status=1',0);
			$files = logic('upgrade')->zip2web($upgrade_file, $upgrade_tmp_dir);
			isset($files['__extract_error__']) && $this->Messager($files['__error_string__'], null);
			$backup_url=$url."&step=backup";
			if ($quick == 'yes')
			{
				$this->Messager('正在开始升级...', $backup_url, 0);
			}
			include handler('template')->file('@admin/upgrade_change_list');
			exit;
		}
		
				if ($step=='backup') 
		{
			logic('upgrade')->upgrade2start();
			
			$original_path=ROOT_PATH;			$backup_path=ROOT_PATH.'backup/'.SYS_VERSION.'-'.SYS_BUILD.'/';			if(!is_dir($backup_path)) {
				IoHandler::MakeDir($backup_path,0777);
			}
			clearstatcache();
			
			$error_found = logic('upgrade')->web2backup($upgrade_tmp_dir, $backup_path);
			if ($error_found == 'ok')
			{
				$error_found = logic('upgrade')->web2upgrade($upgrade_tmp_dir, $original_path);
			}
			if ($error_found != 'ok')
			{
				$msg = '<div style="width:700px;text-align:left;">备份或者升级网站文件时出错，程序无法继续执行！<hr/>';
				$msg .= $error_found;
				$msg .= '<hr/>请您检查相应文件权限后，<a href="'.$url.'&step=backup">点击此处</a> 重新升级';
				$msg .= '</div>';
				$this->Messager($msg, null);
			}
			$this->Messager("正在升级中，请勿关闭窗口...", $url, 0);
		}
				logic('upgrade')->upgrade2data($upgrade_tmp_dir);
				logic('upgrade')->upgrade2update($upgrade_tmp_dir, $original_path);
				logic('upgrade')->upgrade2clear($upcName);
				logic('upgrade')->upgrade2finish();
				$msg="升级已经完成！ <br/><br/><a href='admin.php?mod=index&code=home'>返回控制面板首页</a>";
		$this->Messager($msg, null);
	}
	function clearCache()
	{
		clearcache();

		$msg="缓存已清空，升级完成。<br>";
		$this->Messager($msg, 'admin.php?mod=index&code=home');
	}
	
	public function upsManual()
	{
		$op = get('op');
		if ($op != '')
		{
			if ($op == 'token_close')
			{
				logic('upgrade')->TokenMe(false);
				$this->Messager('临时令牌已经删除，手动升级通道成功关闭！', '?mod=index&code=home');
			}
		}
		$upsParser = logic('upgrade')->CreateToken();
		include handler('template')->file('@admin/upgrade_manual');
		exit;
	}
}

?>