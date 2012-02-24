<?php

/**
 * 入口文件：index
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package load
 * @name index.php
 * @version 1.0
 */

define('DEBUG',false);


class WEB_BASE_ENV_DFS
{
	public static $APPNAME = 'index';
	public static $MODULES = array('index','list','buy','login','get_password','seller','account','me','openapi','misc','callback','pingfore','subscribe','upload','search','wap','apiz','address','recharge','catalog','prize','html');
}

?>