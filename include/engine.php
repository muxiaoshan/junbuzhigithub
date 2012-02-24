<?php

/**
 * 自动化管理引擎
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package base
 * @name engine.php
 * @version 1.2.1
 */


class STATIC_MAP_DFS
{
	public static $logic = array(
		'product' => 'ProductLogic',
		'order' => 'OrderLogic',
		'misc' => 'MiscLogic',
		'coupon' => 'CouponLogic',
		'me' => 'MeLogic',
		'notify' => 'NotifyLogic',
		'express' => 'ExpressLogic',
		'address' => 'AddressLogic',
		'safe' => 'SafeLogic',
		'pay' => 'PayLogic',
		'service' => 'ServiceLogic',
		'push' => 'PushLogic',
		'subscribe' => 'SubscribeLogic',
		'upload' => 'UploadLogic',
		'isearcher' => 'iSearcherLogic',
		'account' => 'AccountLogic',
		'seller' => 'SellerLogic',
		'delivery' => 'DeliveryLogic',
		'rbac' => 'RBACLogic',
		'acl' => 'AccountLicenceLogic',
		'recharge' => 'RechargeLogic',
		'callback' => 'CallbackLogic',
		'catalog' => 'CatalogLogic',
		'prize' => 'PrizeLogic',
		'db' => 'DBMgrLogic',
		'dev' => 'DevelopmentLogic',
		'html' => 'StaticHTMLMgrLogic',
		'upgrade' => 'UpgradeCtrlLogic'
	);
	public static $handler = array(
		'config' => 'ConfigHandler',
		'cookie' => 'CookieHandler',
		'template' => 'TemplateHandler',
		'member' => 'MemberHandler',
		'upload' => 'UploadHandler',
		'image' => 'ImageHandler',
		'verify' => 'VerifyHandler',
		'io' => 'IoHandler',
		'form' => 'FormHandler'
	);
	public static $driver = array(
		'config' => 'ConfigDriver',
		'database' => 'DatabaseDriver',
		'payment' => 'PaymentDriver',
		'notify' => 'NotifyDriver',
		'cache' => 'CacheDriver',
		'service' => 'ServiceDriver',
		'lock' => 'LockDriver',
		'ulogin' => 'UnionLoginDriver',
		'iaddress' => 'ImportAddressDriver'
	);
	public static $ui = array(
		'widget' => 'WidgetUI',
		'loader' => 'LoaderUI',
		'iimager' => 'iImagerUI',
		'pingfore' => 'PingforeUI',
		'isearcher' => 'iSearcherUI',
		'igos' => 'iGOSUI',
		'catalog' => 'CatalogUI',
		'ad' => 'AdDisplayerUI',
		'style' => 'StyleControllerUI'
	);
	public static $app = array(
		'bshare' => 'bShareAPP',
		'ucard' => 'UserCardAPP',
		'smsw' => 'SMSSWarningAPP'
	);
	public static $zlog = array(
		'product' => 'productZLOG',
		'coupon' => 'couponZLOG',
		'admin' => 'adminZLOG',
		'error' => 'errorZLOG',
		'wips' => 'wipsZLOG'
	);
}


function logic( $name )
{
	return __object_auto_load('logic', $name, STATIC_MAP_DFS::$logic, LOGIC_PATH, 'logic');
}


function handler( $name )
{
	return __object_auto_load('handler', $name, STATIC_MAP_DFS::$handler, LIB_PATH, 'han');
}


function driver( $name )
{
	return __object_auto_load('driver', $name, STATIC_MAP_DFS::$driver, DRIVER_PATH, 'drv');
}


function ui( $name )
{
	return __object_auto_load('ui', $name, STATIC_MAP_DFS::$ui, UI_POWER_PATH, 'ui');
}


class STATIC_OBJ_STORE
{
	public static $objsAutoload = array();
	public static $objsMoSpace = array();
	public static $storageCached = array();
	public static $databaseLinker = null;
	public static $engineClasses = array();
}


function __object_auto_load( $channel, $name, &$map, $dir, $midfix )
{
	$engos = &STATIC_OBJ_STORE::$objsAutoload;
	if ( ! isset($engos['_' . $channel . '_']) )
	{
		$engos['_' . $channel . '_'] = array();
	}
	$ego = &$engos['_' . $channel . '_'];
	if ( isset($ego[$name]) )
	{
		return $ego[$name];
	}
	if ( ! isset($map[$name]) )
	{
		zlog('error')->found('missing.object', $channel.'::'.$name);
		exit('Missing object # '.$channel.'::'.$name);
	}
	if (!class_exists($map[$name], false))
	{
		engine_class_file_load($dir . $name . '.' . $midfix);
	}
	$ego[$name] = new $map[$name]();
	return $ego[$name];
}

function __object_auto_load_file($require_file)
{
	if (!is_file($require_file))
	{
		zlog('error')->found('file.missing', $require_file);
		exit('Missing file: '.basename($require_file));
	}
	return require $require_file;
}


function ini()
{
	$argc = func_num_args();
		$key = func_get_arg(0);
	if ($argc == 1)
	{
		return driver('config')->read($key);
	}
	if ($argc == 2)
	{
		$write = func_get_arg(1);
	}
	if ( $write === INI_DELETE )
	{
		driver('config')->delete($key);
	}
	else
	{
		driver('config')->write($key, $write);
	}
	driver('config')->close();
	return true;
}


function dbc($imax = false)
{
	$lnks = &STATIC_OBJ_STORE::$databaseLinker;
		$driver = $imax ? 'max' : 'old';
	if (isset($lnks[$driver]))
	{
		$lnk = $lnks[$driver];
	}
	else
	{
				$lnks[$driver] = null;
		$lnk = &$lnks[$driver];
	}
	if ( ! is_null($lnk) )
	{
		return $lnk;
	}
	if (false == $imax)
	{
				$lnk = driver('database')->load('mysql');
		$lnk->ServerHost = ini('settings.db_host');
		$lnk->ServerPort = ini('settings.db_port');
		$lnk->Charset(ini('settings.charset'));
		$lnk->DoConnect(ini('settings.db_user'), ini('settings.db_pass'), ini('settings.db_name'), ini('settings.db_persist'));
	}
	else
	{
				$lnk = driver('database')->load('mysql_max');
		$lnk->config(array(
			'debug' => DEBUG,
			'host' => ini('settings.db_host').':'.ini('settings.db_port'),
			'username' => ini('settings.db_user'),
			'password' => ini('settings.db_pass'),
			'database' => ini('settings.db_name'),
						'prefix' => '',
			'charset' => ini('settings.charset'),
			'cached' => 'file://'.CACHE_PATH.'query/'
		));
	}
	return $lnk;
}


function user($uid = null)
{
	return logic('me')->user($uid);
}


function meta($key, $val = false, $life = 0)
{
	return user(0)->field($key, $val, $life);
}


function rewrite($string)
{
	global $rewriteHandler;
	if (!$rewriteHandler)
	{
		return $string;
	}
	$string = $rewriteHandler->formatURL($string);
	return $string;
}


function account($method = null)
{
	$logicAcc = logic('account');
	if (is_null($method))
	{
		return $logicAcc;
	}
	if (method_exists($logicAcc, $method))
	{
		return $logicAcc->$method();
	}
	else
	{
		exit('[ERROR] # account.('.$method.').404');
	}
}


function notify($uid, $class, $data = array())
{
	return logic('notify')->Call($uid, $class, $data);
}


function app($name = null)
{
	if (is_null($name))
	{
				engine_class_file_load(APP_PATH.'system.ctrl');
		return loadInstance('app.kernel.system', 'iSystemCtrlAPP');
	}
	return __object_auto_load('app', $name, STATIC_MAP_DFS::$app, APP_PATH.$name.'/', 'load');
}


function zlog($module = null)
{
	if (is_null($module))
	{
				engine_class_file_load(DRIVER_PATH.'zlog/system.zlog');
		return loadInstance('zlog.kernel.system', 'iSystemZLOG');
	}
		engine_class_file_load(DRIVER_PATH.'zlog/master.zlog');
		return __object_auto_load('zlog', $module, STATIC_MAP_DFS::$zlog, DRIVER_PATH.'zlog/', 'apiz');
}


function engine_class_file_load($path)
{
	$class_loaded = &STATIC_OBJ_STORE::$engineClasses;
	if (isset($class_loaded[$path]))
	{
		return;
	}
	$r = __object_auto_load_file($path.'.php');
	$class_loaded[$path] = time();
	return $r;
}

?>