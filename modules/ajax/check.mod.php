<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename check.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 

class ModuleObject extends MasterObject
{
	var $Config = array(); 	var $ID;

	function ModuleObject(& $config){
		$this->MasterObject($config);
		$this->initMemberHandler();
		$this->ID=$this->Post['id']?(int)$this->Post['id']:(int)$this->Get['id'];
		Load::moduleCode($this);$this->Execute();
	}

	function Execute(){
		switch ($this->Code){
			case 'truename':
				$this->Truename();
				break;
			case 'email':
			
				$this->CheckEmail();
				break;						
		}

	}

	function CheckEmail(){
		$email=trim(urldecode($this->Get['email']));
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_members');
		$is_exists=$this->DatabaseHandler->Select('',"email='{$email}'");
		
		if (UCENTER) {
			include_once(UC_CLIENT_ROOT . './client.php');
			$check_result = uc_user_checkemail($email);
			
			if ($check_result < 1) {
				echo '1';exit;
			}
		}
		
		if($is_exists!=false) {
			echo '1';
		} else {
			echo '0';
		}
		
		exit;
	}
	function Truename(){
		$username=trim(urldecode($this->Get['username']));
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'system_members');
		$is_exists=$this->DatabaseHandler->Select('',"username='{$username}'");
		
		if (UCENTER) {
			include_once(UC_CLIENT_ROOT . './client.php');
			$check_result = uc_user_checkname($username);
			
			if ($check_result < 1) {
				echo '1';exit;
			}
		}
		
		if($is_exists!=false){
			echo '1';
		} else {
			echo '0';
		}
		
		exit;
	}
}
?>