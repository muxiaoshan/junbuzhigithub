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
 




class smsNotifyDriver extends NotifyDriver
{
    private $conf = array();
    function __construct($conf)
    {
        $this->conf = $conf;
    }
    
    public function __default($class, $uid, $data)
    {
        if (!is_numeric($uid)) return;
        $phone = user($uid)->get('phone');
        if (!preg_match('/[0-9]{11}/', $phone)) return;
        $msg = ini('notify.event.'.$class.'.msg.sms');
        if (!$msg) return false;
        
        $this->FlagParser($class.'.sms', $data, $msg);
        logic('push')->add('sms', $phone, array(
            'content' => $msg
        ), 9);
        return 'QUEUE RECEIVED';
    }
}

?>