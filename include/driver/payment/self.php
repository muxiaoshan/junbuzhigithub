<?php

/**
 * 支付方式：余额付款
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package payment
 * @name self.php
 * @version 1.1
 */

class selfPaymentDriver extends PaymentDriver
{
	public function CreateLink($payment, $parameter)
	{
		$html =  '<form action="?mod=callback&pid='.$payment['id'].'" method="post">';
		$html .= '<input type="hidden" name="sign" value="'.$parameter['sign'].'" />';
		$html .= '请输入您的登录密码：<input type="password" name="password" />';
		$html .= '<input type="submit" value=" 提 交 " />';
		$html .= '</form>';
		return $html;
	}
	public function CreateConfirmLink($payment, $order)
	{
		return '?mod=buy&code=tradeconfirm&id='.$order['orderid'];
	}
	public function CallbackVerify($payment)
	{
				if (user()->get('id') < 1)
		{
			return 'VERIFY_FAILED';
		}
		$password = post('password', 'txt');
		if (md5($password) != user()->get('password'))
		{
			return 'VERIFY_FAILED';
		}
		$trade = $this->GetTradeData();
		if ($trade['__order__']['paytype'] != $payment['id'])
		{
            return 'VERIFY_FAILED';
		}
		return $trade['status'];
	}
	public function GetTradeData()
	{
		$sign = post('sign', 'number');
		$order = logic('callback')->Bridge($sign)->SrcOne($sign);
		$order && $product = logic('product')->SrcOne($order['productid']);
		$trade = array();
        $trade['sign'] = $sign;
        $trade['trade_no'] = time();
        $trade['price'] = $order['paymoney'];
        $trade['money'] = 0;
        $trade['nmadd'] = true;
        $trade['status'] = ($product['type'] == 'ticket') ? 'TRADE_FINISHED' : 'WAIT_SELLER_SEND_GOODS';
        $trade['__order__'] = $order;
        return $trade;
	}
	public function StatusProcesser($status)
	{
		return false;
	}
	public function GoodSender($payment, $express, $sign, $type)
	{
		if ($type == 'ticket')
		{
			logic('callback')->Bridge($sign)->Processed($sign, 'TRADE_FINISHED');
		}
		else
		{
			logic('callback')->Bridge($sign)->Processed($sign, 'WAIT_BUYER_CONFIRM_GOODS');
		}
	}
}

?>