<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename sms.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




class smsServiceDriver extends ServiceDriver
{
    public $Debug = false;
    public $Errored = false;
    public $errorMsg = '';
    private $__debugs = array();
    private $__apis = array();
    
    public final function api($driver)
    {
        $api = &$this->__apis[$driver];
        if (!is_null($api))
        {
            return $api;
        }
        $file = dirname(__FILE__).'/sms.'.($driver=='ls'?'ls':'qxt').'.php';
	    include_once $file;
	    $className = $driver.'_smsServiceDriver';
	    $api = new $className();
	    $api->config($this->conf);
	    return $api;
    }
    
    public final function Send($phone, $content)
    {
        return $this->api($this->conf['driver'])->IMSend($phone, $content);
    }
    
    public final function Status()
    {
        return $this->api($this->conf['driver'])->IMStatus();
    }
	
    public function BC_EXPS(&$phone, $content, $max = 100)
    {
    	if (strlen($phone) == 11 || !strstr($phone, ';'))
    	{
    		return false;
    	}
    	$phones = explode(';', $phone);
    	foreach ($phones as $i => $one)
    	{
    		if (!preg_match('/[0-9]{11}/', $one))
    		{
    			unset($phones[$i]);
    		}
    	}
    	if (count($phones) <= $max)
    	{
    		$phone = implode(';', $phones);
    		return false;
    	}
    	$sphone = '';
    	$ii = 0;
    	foreach ($phones as $i => $one)
    	{
    		$sphone .= $one.';';
    		$ii ++ ;
    		if ($ii >= $max)
    		{
    			$sphone = substr($sphone, 0, -1);
    			logic('push')->add('sms', $sphone, array('content' => $content));
    			$sphone = '';
    			$ii = 0;
    		}
    	}
    	return true;
    }
    
    public final function Get($url)
    {
        return dfopen($url, 10485760, '', '', true, 10, 'CENWOR.TTTG.SMS.AGENT.'.SYS_VERSION.'.'.SYS_BUILD);
    }
    
    public final function Debug($msg = null)
    {
        if (!$this->Debug) return;
        if (is_null($msg)) return $this->__debugs;
        list($ms, $s) = explode(' ', microtime());
        $this->__debugs[] = array(
            'timer' => (float)($s+$ms),
            'msg' => $msg."\r\n"
        );
    }
    
    public final function Error($msg)
    {
        $this->Errored = true;
        $this->errorMsg = $msg;
    }
}

?>