<?php

/**
 * 财付通支付接口
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package payment
 * @name tenpay.api.php
 * @version 1.0
 */

class exTenpayAPIDriver
{
    
    private $gateUrl = 'http://service.tenpay.com/cgi-bin/v3.0/payservice.cgi';
    
    private $key = '';
    
    private $parameters = array();
    
    private $debugInfo = '';
    private $tradeStatus = '';
    
    function getGateURL ()
    {
        return $this->gateUrl;
    }
    
    function setGateURL ($gateUrl)
    {
        $this->gateUrl = $gateUrl;
    }
    
    function getKey ()
    {
        return $this->key;
    }
    
    function setKey ($key)
    {
        $this->key = $key;
    }
    
    function getParameter ($parameter)
    {
        return $this->parameters[$parameter];
    }
    
    function setParameter ($parameter, $parameterValue)
    {
        $this->parameters[$parameter] = $parameterValue;
    }
    
    function getAllParameters ()
    {
        return $this->parameters;
    }
    function setAllParameters ()
    {
        
        foreach ($_GET as $k => $v) {
            $this->setParameter($k, $v);
        }
        
        foreach ($_POST as $k => $v) {
            $this->setParameter($k, $v);
        }
    }
    
    function getRequestURL ($type)
    {
        $this->createSign($type);
        $reqPar = "";
        ksort($this->parameters);
        foreach ($this->parameters as $k => $v) {
            $reqPar .= $k . "=" . urlencode($v) . "&";
        }
                $reqPar = substr($reqPar, 0, strlen($reqPar) - 1);
        $requestURL = $this->getGateURL() . "?" . $reqPar;
        return $requestURL;
    }
    function getDebugInfo ()
    {
        return $this->debugInfo;
    }
    function createSign ($type)
    {
        $class = 'createSign_' . $type;
        method_exists($this, $class) && $this->$class();
    }
    private function createSign_direct ()
    {
        $cmdno = $this->getParameter("cmdno");
        $date = $this->getParameter("date");
        $bargainor_id = $this->getParameter("bargainor_id");
        $transaction_id = $this->getParameter("transaction_id");
        $sp_billno = $this->getParameter("sp_billno");
        $total_fee = $this->getParameter("total_fee");
        $fee_type = $this->getParameter("fee_type");
        $return_url = $this->getParameter("return_url");
        $attach = $this->getParameter("attach");
        $spbill_create_ip = $this->getParameter("spbill_create_ip");
        $key = $this->getKey();
        $signPars = "cmdno=" . $cmdno . "&" . "date=" . $date . "&" .
         "bargainor_id=" . $bargainor_id . "&" . "transaction_id=" .
         $transaction_id . "&" . "sp_billno=" . $sp_billno . "&" . "total_fee=" .
         $total_fee . "&" . "fee_type=" . $fee_type . "&" . "return_url=" .
         $return_url . "&" . "attach=" . $attach . "&";
        if ($spbill_create_ip != "") {
            $signPars .= "spbill_create_ip=" . $spbill_create_ip . "&";
        }
        $signPars .= "key=" . $key;
        $sign = strtolower(md5($signPars));
        $this->setParameter("sign", $sign);
    }
    private function createSign_medi ()
    {
        $signPars = "";
        ksort($this->parameters);
        foreach ($this->parameters as $k => $v) {
            if ("" != $v && "sign" != $k) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars .= "key=" . $this->getKey();
        $sign = strtolower(md5($signPars));
        $this->setParameter("sign", $sign);
    }
    function verifySign ($type)
    {
        $class = 'verifySign_' . $type;
        if (method_exists($this, $class)) {
            return $this->$class();
        }
        return '__NULL__';
    }
    function verifySign_direct ()
    {
        $this->setAllParameters();
        $cmdno = $this->getParameter("cmdno");
        $pay_result = $this->getParameter("pay_result");
        $date = $this->getParameter("date");
        $transaction_id = $this->getParameter("transaction_id");
        $sp_billno = $this->getParameter("sp_billno");
        $total_fee = $this->getParameter("total_fee");
        $fee_type = $this->getParameter("fee_type");
        $attach = $this->getParameter("attach");
        $key = $this->getKey();
        $signPars = "";
                $signPars = "cmdno=" . $cmdno . "&" . "pay_result=" . $pay_result .
         "&" . "date=" . $date . "&" . "transaction_id=" . $transaction_id . "&" .
         "sp_billno=" . $sp_billno . "&" . "total_fee=" . $total_fee . "&" .
         "fee_type=" . $fee_type . "&" . "attach=" . $attach . "&" . "key=" .
         $key;
        $sign = strtolower(md5($signPars));
        $tenpaySign = strtolower($this->getParameter("sign"));
        $this->getParameter("pay_result") == '0' || $tenpaySign = '__FAIL__';
        $this->tradeStatus = ($sign == $tenpaySign) ? 'TRADE_FINISHED' : 'VERIFY_FAILED';
        return $this->tradeStatus;
    }
    function verifySign_medi ()
    {
        $this->setAllParameters();
        $signParameterArray = array('attach', 'buyer_id', 'cft_tid', 'chnid', 
        'cmdno', 'mch_vno', 'retcode', 'seller', 'status', 'total_fee', 
        'trade_price', 'transport_fee', 'version');
                ksort($signParameterArray);
        foreach ($signParameterArray as $k) {
            $v = $this->getParameter($k);
            if (isset($v)) {
                $signPars .= $k . "=" . urldecode($v) . "&";
            }
        }
        $signPars .= "key=" . $this->getKey();
        $sign = strtolower(md5($signPars));
        $tenpaySign = strtolower($this->getParameter("sign"));
        $retcode = $this->getParameter("retcode");
                $status = $this->getParameter("status");
        $sign == $tenpaySign || $status = 0;
        $retcode == '0' || $status = 0;
        $status = (int) $status;
        $STAMap = array(0 => 'VERIFY_FAILED', 1 => 'WAIT_BUYER_PAY', 
        2 => '__address_filled_ok', 3 => 'WAIT_SELLER_SEND_GOODS', 
        4 => 'WAIT_BUYER_CONFIRM_GOODS', 5 => 'TRADE_FINISHED', 
        6 => '__trade_closed', 7 => '__price_changed', 
        8 => '__buyer_refund_apply', 9 => '__refund_ok', 10 => '__refund_closed');
        $this->tradeStatus = isset($STAMap[$status]) ? $STAMap[$status] : $STAMap[0];
        return $this->tradeStatus;
    }
    function TradeStatus ()
    {
        return $this->tradeStatus;
    }
    function urlShow ($service, $url)
    {
        $class = 'urlShow_of_' . $service;
        $this->$class($url);
    }
    function urlShow_of_direct ($url)
    {
        $strHtml = "<html><head>\r\n" .
         "<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">" .
         "<script language=\"javascript\">\r\n" . "window.location.href='" . $url .
         "';\r\n" . "</script>\r\n" . "</head><body></body></html>";
        echo $strHtml;
        exit();
    }
    function urlShow_of_medi ($url)
    {
        $strHtml = "<html><head>\r\n" .
         "<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">" .
         "</head><body></body></html>";
        echo $strHtml;
        exit();
    }
}
?>
