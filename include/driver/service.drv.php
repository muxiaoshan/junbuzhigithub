<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename service.drv.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




class ServiceDriver
{
    public $conf = array();
    public final function load($name)
    {
        $file = dirname(__FILE__).'/service/'.$name.'.php';
	    include_once $file;
	    $className = $name.'ServiceDriver';
	    return new $className();
    }
    public function config($cfg)
    {
        $this->conf = $cfg;
    }
}

?>