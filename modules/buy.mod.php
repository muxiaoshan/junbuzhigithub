<?php

/**
 * 模块：购买流程操作
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name buy.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
        if (MEMBER_ID < 1)
        {
            $this->Messager(__('请先登录！'), '?mod=account&code=login');
        }
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    
    function Main()
    {
        header('Location: .');
    }
    
    function Checkout()
    {
        $this->Title = __('提交订单');
        $id = get('id', 'int');
        $product = logic('product')->BuysCheck($id);
        if (isset($product['false']))
        {
            $this->Messager($product['false']);
        }
        if ($product['type'] == 'prize')
        {
            header('Location: '.rewrite('?mod=prize&code=sign&pid='.$product['id']));
            exit;
        }
        include handler('template')->file('buy_checkout');
    }
    
    function Checkout_save()
    {
        $product_id = post('product_id', 'int');
        $product = logic('product')->BuysCheck($product_id);
        if (isset($product['false']))
        {
            return $this->__ajax_save_failed($product['false']);
        }
        $num_buys = post('num_buys', 'int');
        if (!$num_buys || ($product['oncemax'] > 0 && $num_buys > $product['oncemax']) || $num_buys < $product['oncemin'])
        {
            return $this->__ajax_save_failed(__('请填写正确的购买数量！'));
        }
        $order = logic('order')->GetFree(user()->get('id'), $product_id);
        $order['productnum'] = $num_buys;
        $order['productprice'] = $product['nowprice'];
        $order['extmsg'] = post('extmsg', 'txt');
        if ($product['type'] == 'stuff')
        {
            logic('address')->Accessed('order.save', $order);
            logic('express')->Accessed('order.save', $order);
        }
                logic('notify')->Accessed('order.save', $order);
                $order['process'] = '__CREATE__';
        $order['status'] = ORD_STA_Normal;
        logic('order')->Update($order['orderid'], $order);
                $ops = array(
            'status' => 'ok',
            'id' => $order['orderid']
        );
        if (!X_IS_AJAX)
        {
        	header('Location: '.rewrite('?mod=buy&code=order&id='.$order['orderid']));
        	exit;
        }
        echo jsonEncode($ops);
    }
    
    private function __ajax_save_failed($msg)
    {
        $ops = array(
            'status' => 'failed',
            'msg' => $msg
        );
        if (!X_IS_AJAX)
        {
        	$this->Messager($msg, -1);
        }
        echo jsonEncode($ops);
        return false;    
    }
    
    function Order()
    {
        $this->Title = __('确认订单');
        $id = get('id', 'number');
        $order = logic('order')->GetOne($id);
                $order['price_of_product'] = $order['productprice']*$order['productnum'];
        $order['price_of_total'] = $order['price_of_product'];
        logic('address')->Accessed('order.show', $order);
        logic('express')->Accessed('order.show', $order);
        logic('notify')->Accessed('order.show', $order);
        include handler('template')->file('buy_order');
    }
    
    function Order_save()
    {
        $order_id = post('order_id', 'number');
        $order = logic('order')->GetOne($order_id);
        if (user()->get('id') != $order['userid'])
        {
            return $this->__ajax_save_failed(__('您没有权限操作此订单！'));
        }
        if ($order['status'] != ORD_STA_Normal || $order['pay'] == ORD_PAID_Yes)
        {
            return $this->__ajax_save_failed(__('此订单已经不能支付！'));
        }
        $product = logic('product')->BuysCheck($order['productid']);
        if (isset($product['false']))
        {
            return $this->__ajax_save_failed($product['false']);
        }
        $payment_id = post('payment_id', 'int');
                $price_total = $order['productprice']*$order['productnum']+$order['expressprice'];
        $pay_money = $price_total;
        $me_money = user()->get('money');
        if ($payment_id == 1)
        {
            $me_money = 0;
        }
        $use_surplus = post('payment_use_surplus', 'txt');
        if ($use_surplus == 'true' && $me_money > 0)
        {
            $pay_money = $price_total - $me_money;
        }
        $array = array(
            'totalprice' => $price_total,
            'paytype' => $payment_id,
            'paymoney' => $pay_money
        );
        logic('order')->Update($order_id, $array);
        $ops = array(
            'status' => 'ok'
        );
    	if (!X_IS_AJAX)
        {
        	header('Location: '.rewrite('?mod=buy&code=pay&id='.$order_id));
        	exit;
        }
        echo jsonEncode($ops);
    }
    
    function Pay()
    {
        $this->Title = __('订单支付');
        $id = get('id', 'number');
        $order = logic('order')->GetOne($id);
        if (user()->get('id') != $order['userid'])
        {
            $this->Messager('对不起，您没有权限支付此订单！', '?mod=me&code=order');
        }
        if ($order['status'] != ORD_STA_Normal)
        {
        	$this->Messager(__('关于此订单：').logic('order')->STA_Name($order['status']), '?mod=me&code=order');
        }
        if ($order['paytype'] == 0)
        {
                        header('Location: '.rewrite('?mod=buy&code=order&id='.$id));
        }
        if ($order['pay'] == 1)
        {
            $this->Messager(__('此订单已经支付过了！'), '?mod=me&code=order');
        }
        $product = logic('product')->BuysCheck($order['productid']);
        if (isset($product['false']))
        {
            return $this->Messager($product['false']);
        }
        $pay = logic('pay')->GetOne($order['paytype']);
        $parameter = array(
            'name' => $order['product']['flag'],
            'detail' => $order['product']['intro'],
            'price' => $order['paymoney'],
            'sign' => $order['orderid'],
            'notify_url' => ini('settings.site_url').'/index.php?mod=callback&pid='.$pay['id'],
            'product_url' => ini('settings.site_url').'/index.php?view='.$order['productid']
        );
        if ($order['product']['type'] == 'stuff')
        {
            $address = logic('address')->GetOne($order['addressid']);
            $parameter['addr_name'] = $address['name'];
            $parameter['addr_address'] = $address['region'].$address['address'];
            $parameter['addr_zip'] = $address['zip'];
            $parameter['addr_phone'] = $address['phone'];
        }
        $payment_linker = logic('pay')->Linker($pay, $parameter);
        include handler('template')->file('buy_pay');
    }
    
    function TradeConfirm()
    {
        $id = get('id', 'number');
        if (!$id)
        {
            $this->Messager(__('订单号无效！'));
        }
        logic('order')->Processed($id, 'TRADE_FINISHED');
        $this->Messager(__('本次交易已经完成！'), '?mod=me&code=order');
    }
}

?>