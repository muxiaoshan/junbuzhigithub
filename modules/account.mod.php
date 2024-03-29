<?php

/**
 * 模块：账户相关
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name account.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
	public $Username = '';
	public $Password = '';
	public $Secques = '';
	public $IsAdmin = false;

	function ModuleObject( $config )
	{
		$this->MasterObject($config);
				$this->Username = isset($this->Post['username']) ? trim($this->Post['username']) : "";
		$this->Password = isset($this->Post['password']) ? trim($this->Post['password']) : "";
		$this->Secques = quescrypt($this->Post['question'], $this->Post['answer']);
		if ( MEMBER_ID > 0 )
		{
			$this->IsAdmin = $this->MemberHandler->HasPermission('member', 'admin');
		}
				$runCode = Load::moduleCode($this);
		$this->$runCode();
	}
	
	public function Main()
	{
		header('Location: '.rewrite('?mod=me'));
	}
	
	public function Exists()
	{
		$field = get('field', 'txt');
		$value = get('value');
		if (false != $f = filter($value))
		{
			exit(jsonEncode(array('status'=>'failed','result'=>$f)));
		}
		$allows = array(
			'email', 'name', 'phone'
		);
		if (false !== array_search($field, $allows))
		{
			if ($field == 'phone' && !ini('member.phone.unique'))
			{
				$r = false;
			}
			else
			{
				$r = account()->Exists($field, $value);
			}
			$ops = array(
				'status' => 'ok',
				'result' => $r
			);
		}
		else
		{
			$ops = array(
				'status'=>'failed',
				'result' => __('未允许字段')
			);
		}
		exit(jsonEncode($ops));
	}
	function Activate()
	{
		$this->Messager("您还没有通过邮箱验证呢！<a href='?mod=account&code=sendcheckmail&uname=" . urlencode(user()->get('name')) . "'>点这里重新发送认证邮件  </a>", 0);
	}
	
	function Confirm()
	{
		$pwd = get('pwd');
		if ( $pwd == '' ) $this->Messager(__("错误！"));
		$pwdT = authcode(urldecode($pwd), 'DECODE', ini('settings.auth_key'));
		if ($pwdT == '')
		{
						$pwd = authcode($pwd, 'DECODE', ini('settings.auth_key'));
		}
		else
		{
			$pwd = $pwdT;
		}
		if ($pwd == '') $this->Messager('邮箱认证失败，请重发认证邮件或联系网站管理员进行人工审核！', 0);
		$sql = 'select * from ' . TABLE_PREFIX . 'system_members where truename = \'' . $pwd . '\'';
		$query = $this->DatabaseHandler->Query($sql);
		$user = $query->GetRow();
		if ( $user == '' || $user['checked'] == 1 ) $this->Messager(__("用户不存在或已经通过验证！"));
		$ary = array( 
			'checked' => 1 
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'system_members');
		$result = $this->DatabaseHandler->Update($ary, 'truename = \'' . $pwd . '\'');
		$this->Messager(__("邮箱认证成功！请重新登录"), rewrite('?mod=account&code=login'));
	}

	
	function Login()
	{
		if ( MEMBER_ID != 0 and false == $this->IsAdmin )
		{
			$this->Messager("您已经使用用户名 " . MEMBER_NAME . " 登录系统，无需再次登录！", null);
		}
		$loginperm = $this->_logincheck();
		if ( ! $loginperm )
		{
			$this->Messager("累计 5 次错误尝试，15 分钟内您将不能登录。", null);
		}
		$this->Title = "用户登录";
		
		$action = "?mod=account&code=login&op=done";
		$question_select = FormHandler::Select("question", ConfigHandler::get("member", "question_list"), 0);
		$role_type_select = FormHandler::Radio("role_type", ConfigHandler::get("member", "role_type_list"), "normal");
		account()->loginReferer($_SERVER['HTTP_REFERER']);
		include ($this->TemplateHandler->Template("account_login"));
	}

	
	function Login_done()
	{
		$loginperm = $this->_logincheck();
		if ( ! $loginperm )
		{
			$this->Messager("累计 5 次错误尝试，15 分钟内您将不能登录。", null);
		}
		$user = account()->Search('name', $this->Username, 1);
				if ( $user && $user['role_id'] != 2 && $user['checked'] == 0 && ini('product.default_emailcheck') )
		{
			header('Location: '.rewrite('?mod=account&code=activate'));exit;
		}
		$loginR = account()->Login($this->Username, $this->Password, ($_POST['keeplogin'] == 'on'));
		if ($loginR['error'])
		{
			$this->_loginfailed($loginperm);
			$this->Messager($loginR['result'], -1);
		}
		$ref = account()->loginReferer();
		$ref || $ref = '?mod=me';
		$this->Messager(__('登录成功！').$loginR['result'], $ref);
	}
	
	function Login_union()
	{
		$flag = get('flag', 'txt');
		if (!$flag || !ini('alipay.account.login.source.'.$flag)) exit('ERROR: no Union Login Request');
		$html = account('ulogin')->linker($flag);
		include handler('template')->file('@account/login/redirect');
	}
	function Login_callback()
	{
		$from = get('from', 'txt');
		$uuid = account('ulogin')->verify($from);
		if ($uuid !== false)
		{
			if (meta($uuid))
			{
								$result = account('ulogin')->login($uuid);
				$ref = account()->loginReferer();
				$ref || $ref = '?mod=me';
				$this->Messager(__('登录成功！').$result, $ref);
			}
			else
			{
								$data = account('ulogin')->ddata($from);
								include handler('template')->file('account_active');
			}
		}
		else
		{
			$this->Messager(__('快捷登录验证出错！'));
		}
	}
	function Login_active()
	{
		$uuid = post('uuid', 'txt');
		$username = post('username');
		$password = post('password');
		$mail = post('mail', 'txt');
		if (!$mail || !check_email($mail))
		{
			$this->Messager(__('请输入正确的Email地址！'), -1);
		}
		$phone = post('phone', 'number');
		if (ini('member.phone.unique') && account()->Exists('phone', $phone))
		{
			$this->Messager(__('您输入的手机号码已被使用，请重新输入！'), -1);
		}
		$subs = post('subs');
				$result = account('ulogin')->active($uuid, $username, $password, $mail);
		if (!$result)
		{
			$this->Messager(__('帐号激活失败！'));
		}
				if ($subs)
		{
			logic('subscribe')->Add(logic('misc')->City('id'), 'mail', $mail, 'true');
		}
				if ($phone && strlen($phone) == 11)
		{
			user($result)->set('phone', $phone);
			if ($subs)
			{
				logic('subscribe')->Add(logic('misc')->City('id'), 'sms', $phone, 'true');
			}
		}
				$result = account('ulogin')->login($uuid);
		$ref = account()->loginReferer();
		$ref || $ref = '?mod=me';
		$this->Messager(__('登录成功！').$result, $ref);
	}

	
	function _updateLoginFields( $uid )
	{
		$timestamp = time();
		$last_ip = getenv('REMOTE_ADDR');
		$sql = "
		UPDATE
			" . TABLE_PREFIX . 'system_members' . "
		SET
			`login_count`='login_count'+1,
			`lastvisit`='{$timestamp}',
			`lastactivity`='{$timestamp}',
			`lastip`='{$last_ip}'
		WHERE 
			uid={$uid}";
		$query = $this->DatabaseHandler->Query($sql);
		Return $query;
	}

	
	function Logout()
	{
		$logoutR = account()->Logout(MEMBER_NAME);
		$this->Messager($logoutR['result'] . '退出成功', '?index');
	}

	function _logincheck()
	{
		$onlineip = $_SERVER['REMOTE_ADDR'];
		$timestamp = time();
		$query = $this->DatabaseHandler->Query("SELECT count, lastupdate FROM " . TABLE_PREFIX . 'system_failedlogins' . " WHERE ip='$onlineip'");
		if ( $login = $query->GetRow() )
		{
			if ( $timestamp - $login['lastupdate'] > 900 )
			{
				return 3;
			}
			elseif ( $login['count'] < 5 )
			{
				return 2;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 1;
		}
	}

	function _loginfailed( $permission )
	{
		$onlineip = $_SERVER['REMOTE_ADDR'];
		$timestamp = time();
		switch ( $permission )
		{
			case 1 :
				$this->DatabaseHandler->Query("REPLACE INTO " . TABLE_PREFIX . 'system_failedlogins' . " (ip, count, lastupdate) VALUES ('$onlineip', '1', '$timestamp')");
				break;
			case 2 :
				$this->DatabaseHandler->Query("UPDATE " . TABLE_PREFIX . 'system_failedlogins' . " SET count=count+1, lastupdate='$timestamp' WHERE ip='$onlineip'");
				break;
			case 3 :
				$this->DatabaseHandler->Query("UPDATE " . TABLE_PREFIX . 'system_failedlogins' . " SET count='1', lastupdate='$timestamp' WHERE ip='$onlineip'");
				$this->DatabaseHandler->Query("DELETE FROM " . TABLE_PREFIX . 'system_failedlogins' . " WHERE lastupdate<$timestamp-901", 'UNBUFFERED');
				break;
		}
	}

	function Register()
	{
		$this->Title = __('注册');
				$city = logic('misc')->CityList();
		$action = '?mod=account&code=register&op=done';
		include ($this->TemplateHandler->Template("account_register"));
	}

	function Register_done()
	{
		$pwd = post('pwd');
		$ckpwd = post('ckpwd');
				if ( $pwd != $ckpwd )
		{
			$this->Messager("两次密码输入不一致！", -1);
		}
		$truename = post('truename');
		$email = post('email');
		$phone = post('phone', 'number');
		if (ini('member.phone.unique') && account()->Exists('phone', $phone))
		{
			$this->Messager(__('您输入的手机号码已被使用，请重新输入！'), -1);
		}
				$rresult = account()->Register($truename, $pwd, $email, $phone);
		if ($rresult['error'])
		{
			$this->Messager($rresult['result'], -1);
		}
		if ( ! ini('product.default_emailcheck'))
		{
			$keepLogin = true;
						$lresult = account()->Login($truename, $pwd, $keepLogin);
			if ($lresult['error'])
			{
				$this->Messager('注册成功，自动登录失败：'.$lresult['result'], -1);
			}
			$ref = account()->loginReferer();
			$ref || $ref = '?mod=me';
			$ucsynlogin = $lresult['result'];
						if ( post('showemail') )
			{
								logic('subscribe')->Validate(logic('subscribe')->Add($city, 'mail', $email));
				if (preg_match('/[0-9]{8,12}/', $phone))
				{
					logic('subscribe')->Validate(logic('subscribe')->Add($city, 'sms', $phone));
				}
			}
			$this->Messager("注册成功{$ucsynlogin}", $ref);
		}
				$this->registmail($truename, $email);
		$this->Messager($r['result']."感谢您的注册，我们已经给您的邮箱发送了一封邮件请您登录邮箱激活账号！", 0);
	}

		function registmail( $truename, $email )
	{
		$key = authcode($truename, 'ENCODE', ini('settings.auth_key'));
		$mail['title'] = $this->Config['site_name'] . '欢迎您！';
		$mail['content'] = $this->Config['site_name'] . '欢迎您的注册 ，<a href="' . $this->Config['site_url'] . '/?mod=account&code=confirm&pwd=' . urlencode($key) . '">请点击这里激活账号</a>，或者复制 <br/>' . $this->Config['site_url'] . '/?mod=account&code=confirm&pwd=' . urlencode($key) . '到浏览器中';
		logic('service')->mail()->Send($email, $mail['title'], $mail['content']);
	}

	function Sendcheckmail()
	{
		extract($this->Get);
		$uname = urldecode($uname);
				$sql = 'select * from ' . TABLE_PREFIX . 'system_members where username=\'' . $uname . '\' and checked = 0';
		$query = $this->DatabaseHandler->Query($sql);
		$user = $query->GetRow();
		if ( $user != '' )
		{
			$this->registmail($uname, $user['email']);
			$this->Messager("已经发送一封确认信件到您的邮箱去了，请注意查收！", 0);
		}
		$this->Messager("错误，该用户已确认信箱或不存在！", 0);
	}
}
?>