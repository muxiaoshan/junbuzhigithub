<?php

/**
 * 支付方式列表
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package payment
 * @name payment.list.php
 * @version 1.0
 */
 
return array(
	'self' => array(
		'name' => '余额支付',
		'detail' => '使用账户余额进行支付'
	),
	'cod' => array(
		'name' => '货到付款',
		'detail' => '送货上门，当面付款'
	),
	'alipay' => array(
		'name' => '支付宝',
		'detail' => '阿里巴巴旗下的支付宝'
	),
	'tenpay' => array(
		'name' => '财付通',
		'detail' => '腾讯旗下的支付方式'
	),
	'bank' => array(
		'name' => '转账汇款',
		'detail' => '支持银行转帐汇款'
	),
	'recharge' => array(
		'name' => '充值卡',
		'detail' => '本站自有充值卡充值'
	),
	'chinabank' => array(
		'name' => '网银在线',
		'detail' => '网银在线支付（支持国内多家银行网银及银联卡在线支付）'
	)
);

?>