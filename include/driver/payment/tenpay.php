<?php

/**
 * 支付方式：财付通
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package payment
 * @name tenpay.php
 * @version 1.1
 */

class tenpayPaymentDriver extends PaymentDriver
{
    private $is_notify;
    private static $apiLoaded = false;
    private function api($payment = false)
    {
        if (!self::$apiLoaded)
        {
            $ver = '.';
            if ($payment['config']['version'] == '1')
            {
                $ver = '.v1.';
            }
            include DRIVER_PATH.'payment/tenpay.api'.$ver.'php';
            self::$apiLoaded = true;
        }
        $api = loadInstance('driver.payment.tenpay.api', 'exTenpayAPIDriver');
        $payment && $api->setKey($payment['config']['key']);
        return $api;
    }
    
    public function CreateLink($payment, $parameter)
    {
                $parameter['name'] = preg_replace('/\&[a-z]{2,4}\;/i', '', $parameter['name']);
        $parameter['detail'] = str_replace(array('"',"'",'\\','&'), '', $parameter['detail']);
                ENC_IS_GBK || $parameter['name'] = ENC_U2G($parameter['name']);
                $this->api($payment);
                $service = $payment['config']['service'];
        $class = 'INITParameter_'.$service;
        if (!method_exists($this, $class))
        {
            return 'Paylink Created fail';
        }
        $this->$class($payment, $parameter);
        return $this->__BuildForm($this->api()->getRequestURL($service));
    }
    public function INITParameter_direct($payment, $parameter)
    {
                $this->api()->setParameter('cmdno', '1');
        $this->api()->setParameter('fee_type',  '1');
        $this->api()->setParameter('attach',  '');
        $this->api()->setParameter('bank_type',  '0');
        $this->api()->setParameter('cs',  ini('settings.charset'));
        $this->api()->setparameter('date', date('Ymd'));
                $this->api()->setParameter('bargainor_id', $payment['config']['bargainor']);
                $this->api()->setParameter('sp_billno', $parameter['sign']);
        $this->api()->setParameter('transaction_id', $payment['config']['bargainor'].str_pad($parameter['sign'], 18, 0, STR_PAD_LEFT));
        $this->api()->setParameter('total_fee', $parameter['price'] * 100);
        $this->api()->setParameter('return_url', $parameter['notify_url']);
        $this->api()->setParameter('desc', $parameter['name']);
                DEBUG || $this->api()->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
    }
    public function INITParameter_medi($payment, $parameter)
    {
                $this->api()->setParameter('cmdno', '12');
        $this->api()->setParameter('attach', '');
        $this->api()->setParameter('encode_type', ini('settings.charset')=='gbk'?1:2);
        $this->api()->setParameter('version',  '2');
                $this->api()->setParameter('chnid', $payment['config']['chnid']);
        $this->api()->setParameter('seller', $payment['config']['seller']);
                $this->api()->setParameter('mch_desc', '');
        $this->api()->setParameter('mch_name', $parameter['name']);
        $this->api()->setParameter('mch_price', $parameter['price'] * 100);
        $this->api()->setParameter('mch_returl', $parameter['notify_url']);
        $this->api()->setParameter('mch_type', '1');
        $this->api()->setParameter('mch_vno', $parameter['sign']);
        $this->api()->setParameter('need_buyerinfo', '2');
        $this->api()->setParameter('show_url',    $parameter['notify_url']);
        $this->api()->setParameter('transport_desc', '');
        $this->api()->setParameter('transport_fee', '');
                DEBUG || $this->api()->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
    }
    
    public function CreateConfirmLink($payment, $order)
    {
        return '?mod=buy&code=tradeconfirm&id='.$order['orderid'];
    }
    
    public function CallbackVerify($payment)
    {
        return $this->api($payment)->verifySign($payment['config']['service']);
    }
    
    public function GetTradeData()
    {
        $src = 'GET';
        $trade = array();
        $trade['sign'] = logic('safe')->Vars($src, 'sp_billno', 'number');
        $trade['sign'] || $trade['sign'] = logic('safe')->Vars($src, 'mch_vno', 'number');
        $trade['trade_no'] = logic('safe')->Vars($src, 'transaction_id', 'number');
        $trade['trade_no'] || $trade['trade_no'] = logic('safe')->Vars($src, 'cft_tid', 'number');
        $trade['price'] = logic('safe')->Vars($src, 'total_fee', 'float') / 100;
        $trade['money'] = $trade['price'];
        $trade['status'] = $this->api()->TradeStatus();
        return $trade;
    }
    
    public function StatusProcesser($status)
    {
        if (!$this->__Is_Nofity())
        {
            return false;
        }
        if ($status != 'VERIFY_FAILED')
        {
            $url = ini('settings.site_url').'/?mod=me&code=order';
            $service = get('sp_billno') ? 'direct' : 'medi';
            $this->api()->urlShow($service, $url);
        }
        else
        {
            echo 'failed';
        }
        return true;
    }
    
    public function GoodSender($payment, $express, $sign, $type)
    {
        if ($type == 'ticket')
        {
            $tradeStatus = $payment['config']['service'] == 'direct' ? 'TRADE_FINISHED' : 'WAIT_SELLER_SEND_GOODS';
            logic('callback')->Bridge($sign)->Processed($sign, $tradeStatus);
        }
        else
        {
            logic('callback')->Bridge($sign)->Processed($sign, 'WAIT_BUYER_CONFIRM_GOODS');
        }
        return;
    }
    private function __Is_Nofity()
    {
        if (is_null($this->is_notify))
        {
            if (count($_COOKIE) == 0)
            {
                $this->is_notify = true;
            }
            else
            {
                $this->is_notify = false;
            }
        }
        return $this->is_notify;
    }
    
    private function __BuildForm($url)
    {
	    $sHtml = '<form id="pay_submit">';
        $sHtml .= '<a href="'.$url.'" target="_blank" style="background:#ccc;padding:6px 12px;text-decoration:none;color:#000;" onclick="javascript:$.hook.call(\'pay.button.click\');">财付通付款</a>';
        $sHtml .= '</form>';
        return $sHtml;
    }
}

?>