<?php

/**
 * 逻辑区：账户相关
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name account.logic.php
 * @version 1.0
 */

class AccountLogic
{
	
	public function Exists($field, $value)
	{
		$result = $this->Search($field, $value);
		return $result ? true : false;
	}
	
	public function Search()
	{
		$argc = func_num_args();
				$field = $sfield = func_get_arg(0);
		$map = array(
			'id' => 'uid',
			'name' => 'username',
			'mail' => 'email'
		);
		if (array_key_exists($sfield, $map))
		{
			$field = $map[$sfield];
		}
		if ($argc > 1)
		{
			$value = func_get_arg(1);
		}
		$sql_sel = '*';
		foreach ($map as $flag => $src)
		{
			$sql_sel .= ',`'.$src.'` AS `'.$flag.'`';
		}
		$sql = 'SELECT '.$sql_sel.' FROM '.table('members');
		if (isset($value))
		{
			$sql .= ' WHERE `'.$field.'`='.(is_string($value) ? '"'.$value.'"' : $value);
		}
		$limit = 0;
		if ($argc > 2)
		{
			$limit = func_get_arg(2);
			$sql .= ' LIMIT '.$limit;
		}
		$query = dbc()->Query($sql);
		$result = ($limit == 1) ? $query->GetRow() : $query->GetAll();
		return $result;
	}
	
	public function Login($username, $password, $keepLogin = true)
	{
				$aCheckResult = $this->invaidAccount($username, $password);
		if ($aCheckResult)
		{
			return $this->ErrorInf($aCheckResult);
		}
				$exLogin = $this->exLogin($username, $password);
		if ($exLogin['error'])
		{
			return $this->ErrorInf($exLogin['result']);
		}
		$extend = $exLogin['result'];
				$check = handler('member')->CheckMember($username, $password);
		if ($check == -1)
		{
			return $this->ErrorInf(__('无法登录，用户密码错误，您可以有至多 5 次尝试。'));
		}
		elseif ($check == 0)
		{
			return $this->ErrorInf(__('无法登录，用户不存在，您可以有至多 5 次尝试。'));
		}
		$UserFields = handler('member')->GetMemberFields();
				handler('cookie')->SetVar('sid', '', - 365 * 86400 * 50);
		handler('member')->SessionExists = false;
		handler('member')->MemberFields['uid'] = $UserFields['uid'];
		handler('member')->session['uid'] = $UserFields['uid'];
		handler('member')->session['username'] = $UserFields['username'];
		$authcode = authcode("{$UserFields['password']}\t{$UserFields['secques']}\t{$UserFields['uid']}", 'ENCODE');
		if ( $keepLogin )
		{
			$expires = (int)ini('settings.cookie_expire') * 86400;
		}
		else
		{
			$expires = false;
		}
		handler('cookie')->SetVar('auth', $authcode, $expires);
		handler('cookie')->SetVar('cookietime', '2592000', $expires);
				return $this->SuccInf($extend);
	}
	
	public function Logout($username)
	{
		handler('cookie')->ClearAll();
		handler('member')->SessionExists = false;
		handler('member')->MemberFields = array();
				$exLogout = $this->exLogout($username);
		if ($exLogout['error'])
		{
			return $this->ErrorInf($exLogout['result']);
		}
		$extend = $exLogout['result'];
				return $this->SuccInf($extend);
	}
	
	public function RegisterLocal($username, $password, $mail)
	{
		return $this->Register($username, $password, $mail, '', '', 'noExRegister');
	}
	
	public function Register($username, $password, $mail = '', $phone = '', $qq = '', $noExRegister = false)
	{
				$aCheckResult = $this->invaidAccount($username, $password, $mail);
		if ($aCheckResult)
		{
			return $this->ErrorInf($aCheckResult);
		}
				if (logic('account')->Exists('name', $username))
		{
			return $this->ErrorInf('用户名已经存在！');
		}
		if (logic('account')->Exists('mail', $mail))
		{
			return $this->ErrorInf('Email 地址已经被使用！');
		}
		if ($noExRegister)
		{
			$extend = array('ucuid' => 0);
		}
		else
		{
						$exRegister = $this->exRegister($username, $password, $mail);
			if ($exRegister['error'])
			{
				return $this->ErrorInf($exRegister['result']);
			}
			$extend = $exRegister['result'];
		}
				$data = array(
			'username' => $username,
			'truename' => $username,
			'password' => md5($password),
			'phone' => (is_numeric($phone) ? $phone : ''),
			'email' => $mail,
			'role_id' => ini('settings.normal_default_role_id'),
			'checked' => ((ini('product.default_emailcheck') == '1') ? 0 : 1),
			'finder' => handler('cookie')->GetVar('finderid'),
			'findtime' => handler('cookie')->GetVar('findtime'),
			'ucuid' => $extend['ucuid'],
			'regip' => client_ip(),
			'lastip' => client_ip(),
			'regdate' => time()
		);
		$iid = dbc(DBCMax)->insert('members')->data($data)->done();
		if (!$iid)
		{
			return $this->ErrorInf('注册失败！（本地数据库错误）');
		}
				$data['password'] = $password;
		logic('notify')->Call($iid, 'logic.account.register.done', $data);
				return $this->SuccInf($iid);
	}
	
	public function Validated($uid)
	{
		return dbc(DBCMax)->update('members')->data('checked = 1')->where('uid = '.$uid)->done();
	}
	
	public function invaidAccount($username = null, $password = null, $mail = null)
	{
				if (!is_null($username))
		{
						$username = trim($username);
			if ($username == '')
			{
				return '用户名不可以为空！';
			}
						if (false != $f = filter($username))
			{
				return $f;
			}
						if (preg_match('~[\~\`\!\@\#\$\%\^\&\*\(\)\=\+\[\{\]\}\;\:\'\"\,\<\.\>\/\?]~', $username))
			{
				return '用户名不能包含特殊字符！';
			}
		}
				if (!is_null($password))
		{
						$password = trim($password);
			if ($password == '')
			{
				return '密码不可以为空！';
			}
						if (strlen($password) < 4)
			{
				return '密码最少需要4位！';
			}
		}
				if (!is_null($mail))
		{
						if (!check_email($mail))
			{
				return '邮箱地址不正确！';
			}
		}
		return false;
	}
	
	private function exLogin($username, $password)
	{
				if ( false === UCENTER ) return '';
		return loadInstance('logic.account.ex.uc', 'AccountLogic_ex_UCenter')->Login($username, $password);
	}
	
	private function exLogout($username)
	{
				if ( false == UCENTER ) return;
		return loadInstance('logic.account.ex.uc', 'AccountLogic_ex_UCenter')->Logout($username);
	}
	
	private function exRegister($username, $password, $mail)
	{
				if ( false === UCENTER ) return 0;
		return loadInstance('logic.account.ex.uc', 'AccountLogic_ex_UCenter')->Register($username, $password, $mail);
	}
	
	public function loginReferer($ref = null, $ignoreAccountURI = true)
	{
		if (is_null($ref))
		{
						$ref = handler('cookie')->GetVar('loginref');
			if (!$ref || $ref == '')
			{
				return false;
			}
			else
			{
				handler('cookie')->SetVar('loginref', '', -1);
				return $ref;
			}
		}
		else
		{
			if ($ignoreAccountURI && stristr($ref, 'account')) return;
						handler('cookie')->SetVar('loginref', $ref);
		}
	}
	
	public function SuccInf($text)
	{
		return array(
			'error' => false,
			'result' => $text
		);
	}
	
	public function ErrorInf($text)
	{
		return array(
			'error' => true,
			'result' => $text
		);
	}
	
	public function GetFreeName($format = '{$UNAME$}')
	{
				$mkey = 'logic.account.freename.mc';
		$mf = fcache($mkey, dfTimer('com.account.freename.mc.cache'));
		if (!$mf)
		{
			$mf = dbc(DBCMax)->select('members')->in('COUNT(1) AS MC')->limit(1)->done();
			fcache($mkey, $mf);
		}
		$mc = (int)$mf['MC'];
		if ($mc < 300)
		{
			$length = 2;
		}
		elseif ($mc < 10000)
		{
			$length = 3;
		}
		else
		{
						$length = 4;
		}
		$rand = random($length);
		$username = str_replace('{$UNAME$}', $rand, $format);
		if ($this->Exists('name', $username))
		{
			return $this->GetFreeName($format);
		}
		return $username;
	}
	
	public function ulogin()
	{
		return loadInstance('logic.account.ulogin', 'AccountLogic_uLogin');
	}
}


class AccountLogic_uLogin
{
	
	public function wlist()
	{
		if (ini('alipay.account.login.enabled'))
		{
			$list = ini('alipay.account.login.source');
			include handler('template')->file('@account/login/union_list');
		}
	}
	
	public function linker($flag)
	{
		return driver('ulogin')->api($flag)->linker();
	}
	
	public function verify($flag)
	{
		$uid = driver('ulogin')->api($flag)->verify();
		return $uid ? 'ul.'.$flag.'.'.$uid : false;
	}
	
	public function ddata($flag)
	{
		$data = driver('ulogin')->api($flag)->transdata();
		$uNameSearchs = array();
		$uNameSearchs['realName'] = $data['username'];
		$data['mail'] != '' && $uNameSearchs['email'] =  substr($data['mail'], 0, strpos($data['mail'], '@'));
		$data['phone'] != '' && $uNameSearchs['phone'] = $data['phone'];
		$foundName = $this->find_username_in_list($uNameSearchs);
		$data['username'] = $foundName ? $foundName : account()->GetFreename('ul.{$UNAME$}');
		$data['password'] = random(18);
		return $data;
	}
	
	private function find_username_in_list($nameList)
	{
		foreach ($nameList as $i => $username)
		{
			if ($username && !account()->Exists('name', $username))
			{
				return $username;
			}
		}
		return false;
	}
	
	public function login($uuid)
	{
		$acf = meta($uuid);
		list($username, $password) = explode("\n", $acf);
				$lresult = account()->Login($username, $password, false);
				$this->mksource($uuid);
		return $lresult['result'];
	}
	
	public function active($uuid, $username, $password, $mail)
	{
		if (account()->Exists('name', $username))
		{
						$username = account()->GetFreename('ul.{$UNAME$}');
		}
				$rresult = account()->Register($username, $password, $mail);
		if ($rresult['error'])
		{
			return false;
		}
				$mf = account()->Search('id', $rresult['result'], 1);
		$username = $mf['name'];
				list($action, $source, $luid) = explode('.', $uuid);
		meta('luid_'.$rresult['result'], $luid);
				$acf = $username."\n".$password."\n".$rresult['result'];
		meta($uuid, $acf);
				return $rresult['result'];
	}
	
	public function token($luid = null, $token = null)
	{
		if (is_null($token))
		{
			if (is_null($luid))
			{
								$uid = user()->get('id');
				$luid = meta('luid_'.$uid);
			}
			return meta('token_'.$luid);
		}
				meta('token_'.$luid, $token);
	}
	
	private function mksource($uuid)
	{
		list($action, $source, $luid) = explode('.', $uuid);
		handler('cookie')->SetVar('loginSource', $source);
	}
	
	public function UserPasswd($uid, $passwd)
	{
		$luid = meta('luid_'.$uid);
		if (!$luid) return false;
		$alikey = 'ul.alipay.'.$luid;
		$account = meta($alikey);
		list($usrname, $usrpasswd, $usrid) = explode("\n", $account);
				$usrpasswd = $passwd;
		$acf = $usrname."\n".$usrpasswd."\n".$usrid;
		meta($alikey, $acf);
		return true;
	}
}


class AccountLogic_ex_UCenter
{
	
	public function Login($username, $password)
	{
		include_once UC_CLIENT_ROOT.'client.php';
				$locUser = logic('account')->Search('name', $username, 1);
				list($uc_uid, $uc_username, $uc_password, $uc_mail, $uc_same_username) = uc_user_login($username, $password);
				if ($uc_uid > 0 && !$locUser)
		{
						$r = logic('account')->RegisterLocal($username, $password, $uc_mail);
			if ($r['error'])
			{
				return logic('account')->ErrorInf('UC用户注册到本地失败：'.$r['result']);
			}
			user($r['result'])->set('ucuid', $uc_uid);
		}
				if ($uc_uid == -1 && $locUser && $locUser['password'] == md5($password))
		{
						$uc_uid = uc_user_register($username, $password, $locUser['email']);
						$errList = array(
				-1 => '用户名不合法',
				-2 => '包含不允许注册的词语',
				-3 => '用户名已经存在',
				-4 => 'Email 格式有误',
				-5 => 'Email 不允许注册',
				-6 => '该 Email 已经被注册'
			);
			if ($uc_uid < 0)
			{
				return logic('account')->ErrorInf('本地用户注册到UC失败：'.$errList[$uc_uid]);
			}
			user($locUser['id'])->set('ucuid', $uc_uid);
		}
				if ($uc_uid > 0 && $locUser && md5($uc_password) != $locUser['password'])
		{
						user($locUser['id'])->set('password', md5($uc_password));
			return logic('account')->ErrorInf('本系统已经启用了UC，请使用您UC账户的密码进行登录！');
		}
		$synLogin = uc_user_synlogin($uc_uid);
		return logic('account')->SuccInf($synLogin);
	}
	
	public function Logout($username)
	{
		include_once UC_CLIENT_ROOT.'client.php';
		$synLogout = uc_user_synlogout();
		return logic('account')->SuccInf($synLogout);
	}
	
	public function Register($username, $password, $mail)
	{
		include_once UC_CLIENT_ROOT.'client.php';
				$uc_uid = uc_user_register($username, $password, $mail);
				$errList = array(
			-1 => '用户名不合法',
			-2 => '包含不允许注册的词语',
			-3 => '用户名已经存在',
			-4 => 'Email 格式有误',
			-5 => 'Email 不允许注册',
			-6 => '该 Email 已经被注册'
		);
		if ($uc_uid < 0)
		{
			return logic('account')->ErrorInf('注册到UC失败：'.$errList[$uc_uid]);
		}
		return logic('account')->SuccInf(array('ucuid' => $uc_uid));
	}
}

?>