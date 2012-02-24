<?php

/**
 * 模块：动态数据显示
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name list.mod.php
 * @version 1.2
 */

class ModuleObject extends MasterObject
{
	var $city;
	var $cityname;
	var $ProductLogic;
	var $PayLogic;
	var $MeLogic;
	var $OrderLogic;
	function ModuleObject( $config )
	{
		$this->MasterObject($config); 		Load::logic('product');
		$this->ProductLogic = new ProductLogic();
		Load::logic('pay');
		$this->PayLogic = new PayLogic();
		Load::logic('me');
		$this->MeLogic = new MeLogic();
		Load::logic('order');
		$this->OrderLogic = new OrderLogic();
		$this->ID = ( int )($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);
		$this->CacheConfig = ConfigHandler::get('cache'); 		$this->ShowConfig = ConfigHandler::get('show'); 		$runCode = Load::moduleCode($this, $this->Code);
		$this->$runCode();
	}
	function Main()
	{
		header('Location: '.rewrite('?mod=list&code=ask'));
	}
	function Ask()
	{
		$this->Title = __('在线问答');
		$action = '?mod=list&code=doquestion';
		include ($this->TemplateHandler->Template("ask"));
	}
	function Doquestion()
	{
		extract($this->Post);
		if ( MEMBER_ID < 1 ) $this->Messager(__('您必须先登录才能发表您的提问！'));
		if ( $question == '' ) $this->Messager(__('问题不可以为空哦！'));
		if ( $a = filter($question) ) $this->Messager($a);
		$ary = array( 
			userid => MEMBER_ID, username => MEMBER_NAME, content => $question, time => time() 
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_question');
		$result = $this->DatabaseHandler->Insert($ary);
		$ary['time'] = date('Y-m-d H:i:s', $ary['time']);
		notify(MEMBER_ID, 'list.ask.new', $ary);
		$this->Messager(__("提问成功，请等待管理员的回复！"), "?mod=list&code=ask");
		exit();
	}
	function Business()
	{ 		$this->Title = __('商务合作');
		$action = '?mod=index&code=doteamwork';
		include ($this->TemplateHandler->Template('business'));
	}
	function Doteamwork()
	{
		$this->__filter_post('name,phone,elsecontat,content');
		if ( $this->Post['name'] == '' || $this->Post['phone'] == '' || $this->Post['content'] == '' ) $this->Messager("缺少必要参数，请正确填写！");
		$ary = array( 
			'name' => $this->Post['name'], 'phone' => $this->Post['phone'], 'elsecontat' => $this->Post['elsecontat'], 'content' => $this->Post['content'], 'time' => time(), 'type' => 2, 'readed' => 0 
		);
		$this->MeLogic->UserMsg($ary);
		$this->Messager(__("我们已经记录下您的合作信息，我们将尽快给您回复！"), "?mod=list&code=business");
	}
	function Feedback()
	{ 		$this->Title = __('意见反馈');
		$action = '?mod=index&code=dofeedback';
		include ($this->TemplateHandler->Template('feedback'));
	}
	function Dofeedback()
	{
		$this->__filter_post('name,phone,elsecontat,content');
		if ( $this->Post['name'] == '' || $this->Post['phone'] == '' || $this->Post['content'] == '' ) $this->Messager("缺少必要参数，请正确填写！");
		$ary = array( 
			'name' => $this->Post['name'], 'phone' => $this->Post['phone'], 'elsecontat' => $this->Post['elsecontat'], 'content' => $this->Post['content'], 'time' => time(), 'type' => 1, 'readed' => 0 
		);
		$this->MeLogic->UserMsg($ary);
		$this->Messager(__("我们已经记录下您的反馈信息，感谢您对本站的支持！"), "?mod=list&code=feedback");
	}
	private function __filter_post($fields)
	{
		$list = explode(',', $fields);
		foreach ($list as $i => $fid)
		{
			$moyoCNT = &$this->Post[$fid];
			$moyoAFS = filter($moyoCNT);
			$moyoAFS && $this->Messager($moyoAFS);
		}
	}
	function Deals()
	{
		$this->Title = __('历史团购');
		$product = logic('product')->GetList(logic('misc')->City('id'), PRO_ACV_No);
		include ($this->TemplateHandler->Template('deals'));
	}
	function Sendemail()
	{
		extract($this->Post);
		if ( ! check_email($email) ) $this->Messager(__("邮箱地址有误！"));
		if ( isset($del) )
		{
			$this->MeLogic->mail($email, $city, 0);
		}
		else
		{
			$this->MeLogic->mail($email, $city, 1);
		}
		$this->Messager(__("操作成功！"), "?");
	}
	function Invite()
	{
		$this->Title = __('邀请有奖');
		if ( MEMBER_ID < 1 )
		{
			$this->Messager(__("请您先注册或登录！"), '?mod=account&code=login');
		}
		$finder = $this->MeLogic->finderList(user()->get('id'));
		include ($this->TemplateHandler->Template("invite"));
	}
	
	function Ckticket()
	{
		$this->Title = __('消费卷查询');
		$action = '?mod=list&code=dockticket';
		include ($this->TemplateHandler->Template("tttuangou_ckticket"));
	}
	function Dockticket()
	{
		extract($this->Get);
		if ( $number == '' ) exit('<font color="red">编号不能为空！</font>');
		$sql = 'select * from ' . TABLE_PREFIX . 'tttuangou_ticket where number = \'' . $number . '\'';
		$query = $this->DatabaseHandler->Query($sql);
		$ticket = $query->GetRow();
		if ( $ticket && $ticket['status'] == TICK_STA_Unused )
		{
						$this->MeLogic->ticketCheck($ticket);
		}
		if ( $do == 'check' )
		{
			if ( empty($ticket) )
			{
				exit('<font color="red">该团购券不存在！</font>');
			}
			else
			{
				if ( $ticket['status'] == TICK_STA_Unused )
				{
					$msg = '<font color="green">该团购券可以使用</font>';
				}
				elseif ( $ticket['status'] == TICK_STA_Used )
				{
					$msg = '<font color="blue">该团购券已经被使用，消费时间：' . $ticket['usetime'] . '</font>';
				}
				else
				{
					$msg = '<font color="red">该团购券已过期！</font>';
				}
				exit($msg);
			}
			;
		}
		elseif ( $do == 'getname' )
		{
			if ( empty($ticket) )
			{
				exit('<font color="red">无效的团购券！</font>');
			}
			$sql = 'select s.userid,p.name from ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id  where p.id = ' . $ticket['productid'];
			$query = $this->DatabaseHandler->Query($sql);
			if ( $query )
			{
				$result = $query->GetRow();
				exit($result['name'].'<br/> X <font color="red"><b>'.$ticket['mutis'].'</b></font> 份');
			}
			else
			{
				exit('<font color="red">没有找到该产品！</font>');
			}
		}
		else
		{
			if ( empty($ticket) )
			{
				exit('<font color="red">该团购券不存在！</font>');
			}
			elseif ( $ticket['status'] == TICK_STA_Used )
			{
				exit('<font color="blue">该团购券已经被使用，消费时间：' . $ticket['usetime'] . '</font>');
			}
			elseif ( $ticket['status'] != TICK_STA_Unused )
			{
				exit('<font color="red">该团购券已过期！</font>');
			}
			;
			$sql = 'select s.userid from ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id  where p.id = ' . $ticket['productid'];
			$query = $this->DatabaseHandler->Query($sql);
			$product = $query->GetRow();
			if ( $product['userid'] != MEMBER_ID ) exit('<font color="red">此团购券不属于您的产品！</font>');
			if ( $password == $ticket['password'] )
			{
				$ary = array( 
					'status' => TICK_STA_Used, 'usetime' => date('Y-m-d H:i:s', time()) 
				);
				$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_ticket');
				$result = $this->DatabaseHandler->Update($ary, 'ticketid=' . $ticket['ticketid']);
								logic('notify')->Call($ticket['uid'], 'logic.coupon.Used', array(
					'productflag' => $product['flag'],
					'number' => $ticket['number'],
					'time' => $ary['usetime']
				));
				zlog('coupon')->used($ticket);
				exit('<font color="green">团购券正确，已经成功使用！</font>');
			}
			;
			exit('<font color="red">团购券密码错误！</font>');
		}
	}
}
?>
