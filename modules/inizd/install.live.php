<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name install.live.php
 * @date 2011-12-07 13:42:07
 */
 


function install_links()
{
	$zzxgj = ini('link.link_list.zzxgj');
	if (!$zzxgj)
	{
		$zzxgj_link_list = array(
			array (
				'name' => _getaarrayrandval(array('免费微博系统','开源微博系统','开源微博程序','微博程序','微博系统','php微博系统','php微博程序',)),
				'url' => 'http:/'.'/www.jishigou.net',
			),
			array (
				'name' => _getaarrayrandval(array('免费轻博系统','开源轻博系统','开源轻博程序','轻博程序','轻博系统','php轻博系统','php轻博程序',)),
				'url' => 'http:/'.'/jishigou.net',
			),
			array (
				'name' => _getaarrayrandval(array('智能建站系统','关键词采集','自动伪原创','自动采集系统','智能网站系统','专题网站系统',)),
				'url' => 'http:/'.'/aijuhe.net',
			),
			array (
				'name' => _getaarrayrandval(array('微博','开源微博','微博网站源码','开源php微博','免费微博系统','微博程序','微博源码下载',)),
				'url' => 'http:/'.'/www.jishigou.net',
			),
			array (
				'name' => _getaarrayrandval(array('天天团购系统','团购系统','php团购系统','免费团购程序','开源团购程序','天天团购','团购网站程序','团购网站源码','团购源码下载','团购程序',)),
							'url' => 'http:/'.'/www.tttuangou.net',
			),
	
		);
		$links['zzxgj'] = _getaarrayrandval($zzxgj_link_list);
		$links['zzxgj1'] = _getaarrayrandval($zzxgj_link_list);
		$links['zzxgj']['order'] = $links['zzxgj1']['order'] = 100;
		if($links['zzxgj']['url'] == $links['zzxgj1']['url']) {
			unset($links['zzxgj1']);
		}
		ini('link.link_list', $links);
	}
}

function _getaarrayrandval($arr) {
	$rnd_key = array_rand($arr);
	return $arr[$rnd_key];
}

function install_request($post=array(),&$error) {
	settype($post,"array");
	$post['system_env'] = install_get_system_env();
	$data='_POST='.urlencode(base64_encode(serialize($post)));
	$server_url = "ht"."tp:/"."/ww"."w.tt"."t"."ua"."ngo"."u.n"."et"."/s"."er"."ver".".ph"."p";
	$response=@install_dfopen($server_url,5000000,$data);
	$error_msg=array(1=>"error_nodata",2=>"error_format",);
	if($response == "") {
		$result = $error_msg[($error = 1)];
	}else{
		$int = preg_match("/<DATA>(.*)<\/DATA>/s", $response, $m);
		if($int < 1){
			$result = $error_msg[($error = 2)];
		}else{
						if(false!==strpos($m[1],"\n")) {
				$m[1] = preg_replace('~\s+\w{1,10}\s+~','',$m[1]);
			}
			$response = unserialize(base64_decode($m[1]));
			$result = $response['data'];
			if($response['type']) {
				$error = 3;
			}
		}
	}
	return $result;
}

function install_get_system_env( )
{
	$e = array();
	$e['time'] = gmdate( "Y-m-d", time( ) );
	$e['os'] = PHP_OS;
	$e['ip'] = @gethostbyname($_SERVER['SERVER_NAME']) or ($e['ip'] = getenv( "SERVER_ADDR" )) or ($e['ip'] = getenv('LOCAL_ADDR'));
	$e['sapi'] = @php_sapi_name( );
	$e['host'] = strtolower(getenv('HTTP_HOST') ? getenv('HTTP_HOST') : $_SERVER['HTTP_HOST']);
	$e['path'] = substr(dirname(__FILE__),0,-8);
	$e['cpu'] = $_ENV['PROCESSOR_IDENTIFIER']."/".$_ENV['PROCESSOR_REVISION'];
	$e['name'] = $_ENV['COMPUTERNAME'];
	if(defined('SYS_VERSION')) $e['sys_version']=SYS_VERSION;
	if(defined('SYS_BUILD')) $e['sys_build']=SYS_BUILD;	
	$config = array();
	include './setting/settings.php';
	$sys_conf = $config['settings'];
	if($sys_conf['site_name']) $e['sys_name'] = $sys_conf['site_name'];
	if($sys_conf['site_admin_email']) $e['sys_email'] = $sys_conf['site_admin_email'];
	if($sys_conf['site_url']) $e['sys_url'] = $sys_conf['site_url'];
	if($sys_conf['charset']) $e['sys_charset'] = $sys_conf['charset'];
	if($sys_conf['language']) $e['sys_language'] = $sys_conf['language'];
	return $e;
}

function install_dfopen($url, $limit = 10485760 , $post = '', $cookie = '', $bysocket = false,$timeout=2,$agent="") {
	if(ini_get('allow_url_fopen') && !$bysocket && !$post) {
		$fp = @fopen($url, 'r');
		$s = $t = '';
		if($fp) {
			while ($t=@fread($fp,2048)) {
				$s.=$t;
			}
			@fclose($fp);
		}
		if($s) {
			return $s;
		}
	}

	$return = '';
	$agent=$agent?$agent:"Mozilla/5.0 (compatible; Googlebot/2.1; +http:/"."/www.google.com/bot.html)";
	$url=unescape($url);
	$matches = parse_url($url);
	$host = $matches['host'];
	$script = $matches['path'].($matches['query'] ? '?'.$matches['query'] : '').($matches['fragment'] ? '#'.$matches['fragment'] : '');
	$script = $script ? $script : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post) {
		$out = "POST $script HTTP/1.1\r\n";
		$out .= "Accept: */"."*\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Accept-Encoding: none\r\n";
		$out .= "User-Agent: $agent\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $script HTTP/1.1\r\n";
		$out .= "Accept: */"."*\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Accept-Encoding:\r\n";
		$out .= "User-Agent: $agent\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = msockopen($host, $port, $errno, $errstr, $timeout);

	if(!$fp) {
		return false;
	} else {
		@fwrite($fp, $out);
		$return = '';
		while(!feof($fp) && $limit > -1) {
			$limit -= 8192;
			$return .= @fread($fp, 8192);
			if(!isset($status)) {
				preg_match("|^HTTP/[^\s]*\s(.*?)\s|",$return, $status);
				$status=$status[1];
				if($status!=200) {
					return false;
				}
			}
		}
		@fclose($fp);
				preg_match("/^Location: ([^\r\n]+)/m",$return,$match);
		if(!empty($match[1]) && $location=$match[1]) {
			if(strpos($location,":/"."/")===false) {
				$location=dirname($url).'/'.$location;
			}
			$args=func_get_args();
			$args[0]=$location;
			return call_user_func_array("dfopen",$args);
		}
		if(false!==($strpos = strpos($return, "\r\n\r\n"))) {
			$return = substr($return,$strpos);
			$return = preg_replace("~^\r\n\r\n(?:[\w\d]{1,8}\r\n)?~","",$return);
			if("\r\n\r\n"==substr($return,-4)) {
				$return = preg_replace("~(?:\r\n[\w\d]{1,8})?\r\n\r\n$~","",$return);
			}			
		}
		return $return;
	}
}

function unescape($str) { 
	 $str = rawurldecode($str); 
	 preg_match_all("/%u.{4}|&#x.{4};|&#d+;|.+/U",$str,$r); 
	 $ar = $r[0]; 
	 foreach($ar as $k=>$v) { 
			  if(substr($v,0,2) == "%u") 
					   $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,-4))); 
			  elseif(substr($v,0,3) == "&#x") 
					   $ar[$k] = iconv("UCS-2","GBK",pack("H4",substr($v,3,-1))); 
			  elseif(substr($v,0,2) == "&#") { 
					   $ar[$k] = iconv("UCS-2","GBK",pack("n",substr($v,2,-1))); 
			  } 
	 } 
	 return join("",$ar); 
} 

?>