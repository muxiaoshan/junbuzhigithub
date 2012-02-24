<?php

/**
 * 逻辑区：消息推送
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name push.logic.php
 * @version 1.0
 */

class PushLogic
{
	
	public function add($type, $target, $data, $pr = 1)
	{
		$queue = array(
			'type' => $type,
			'target' => $target,
			'data' => logic('push')->datapas($data, 'en'),
			'update' => time(),
			'pr' => $pr
		);
		dbc()->SetTable(table('push_queue'));
		dbc()->Insert($queue);
	}
	
	public function addi($type, $target, $data)
	{
		$runCode = 'run_'.$type;
		$this->$runCode($target, $data);
	}
	public function run($max, $class = null)
	{
		$lck = 'logic.push.running'.(is_null($class)?'.mix':'.'.$class);
		$MT = ini('service.push.mthread');
		if (!$MT && locked($lck))
		{
			return;
		}
		$MT || locked($lck, true);
		ignore_user_abort(1);
		set_time_limit(0);
		$ix = 0;
		while ($ix < $max)
		{
			$sql = 'SELECT * FROM '.table('push_queue').' WHERE '.(is_null($class)?'':'type="'.$class.'" AND').' rund="false" ORDER BY pr DESC LIMIT 1';
			$queue = dbc()->Query($sql)->GetRow();
			if (!$queue)
			{
				$ix = $max + 1;
				continue;
			}
			$runCode = 'run_'.$queue['type'];
			$result = $this->$runCode($queue['target'], logic('push')->datapas($queue['data'], 'run'));
			$this->__update($queue['id'], $result);
			$ix ++;
		}
		$this->__clear();
		$MT || locked($lck, false);
	}
	
	public function log($type, $driver, $target, $data, $result)
	{
		$data = array(
			'type' => $type,
			'driver' => $driver,
			'target' => $target,
			'data' => logic('push')->datapas($data, 'en'),
			'result' => $result,
			'update' => time()
		);
		dbc()->SetTable(table('push_log'));
		dbc()->Insert($data);
	}
	
	private function __update($id, $result)
	{
		$data = array(
			'rund' => 'true',
			'result' => $result,
			'update' => time()
		);
		dbc()->SetTable(table('push_queue'));
		dbc()->Update($data, 'id='.$id);
	}
	
	private function __clear()
	{
		$flagKey = 'push.logic.clear.flag';
		$timeInv = dfTimer('com.push.queue.clean');
		$flag = fcache($flagKey, $timeInv);
		if (!$flag)
		{
			$timeBefore = time() - $timeInv;
			dbc()->SetTable(table('push_queue'));
			dbc()->Delete('', 'rund="true" AND `update`<'.$timeBefore);
			fcache($flagKey, 'mark');
		}
	}
	
	private function run_mail($target, $data)
	{
		$result = logic('service')->mail()->Send($target, $data['subject'], $data['content']);
		if ($result == 1)
		{
			$result = __('邮件发送成功！');
		}
		return $result;
	}
	
	private function run_sms($target, $data)
	{
		return logic('service')->sms()->Send($target, $data['content']);
	}
	
	public function template()
	{
		return loadInstance('logic.push.template', 'PushLogic_Template');
	}
	
	public function ListQueue($rund = 'false', $type = null)
	{
		$sql_limit_type = '1';
		if ($type !== null)
		{
			$sql_limit_type = 'type="'.$type.'"';
		}
		$sql = 'SELECT * FROM '.table('push_queue').' WHERE '.$sql_limit_type.' AND rund="'.$rund.'" ORDER BY id DESC';
		$sql = page_moyo($sql);
		$r = dbc(DBCMax)->query($sql)->done();
		if (!$r) return array();
		foreach ($r as $i => $o)
		{
			$r[$i]['data'] = logic('push')->datapas($o['data'], 'de');
		}
		return $r;
	}
	
	public function ListLog($type = null)
	{
		$sql_limit_type = '1';
		if ($type !== null)
		{
			$sql_limit_type = 'type="'.$type.'"';
		}
		$sql = 'SELECT * FROM '.table('push_log').' WHERE '.$sql_limit_type.' ORDER BY id DESC';
		$sql = page_moyo($sql);
		$r = dbc(DBCMax)->query($sql)->done();
		foreach ($r as $i => $o)
		{
			$r[$i]['data'] = logic('push')->datapas($o['data'], 'de');
		}
		return $r;
	}
	
	public function query()
	{
		return loadInstance('logic.push.query', 'PushLogic_Query');
	}
	public function datapas($data, $dir = 'en')
	{
				if ($dir == 'en')
		{
			return base64_encode(serialize($data));
		}
		else
		{
			$array = unserialize(substr($data, 0, 2) == 'a:' ? str_replace("\r", "", $data) : base64_decode($data));
			if ($array && $dir != 'run')
			{
				foreach ($array as $key => $val)
				{
					$array[$key] = stripcslashes($val);
				}
			}
			return $array;
		}
	}
}

/**
 * 扩充类：模板管理
 * @author Moyo <dev@uuland.org>
 */
class PushLogic_Template
{
	public function GetList($type = null)
	{
		$sql_limit_type = '1';
		if (!is_null($type))
		{
			$sql_limit_type = 'type="'.$type.'"';
		}
		$sql = 'SELECT * FROM '.table('push_template').' WHERE '.$sql_limit_type;
		$sql = page_moyo($sql);
		return dbc(DBCMax)->query($sql)->done();
	}
	public function GetOne($id)
	{
		$sql = 'SELECT * FROM '.table('push_template').' WHERE id='.$id;
		return dbc(DBCMax)->query($sql)->limit(1)->done();
	}
	
	public function Search($field, $value, $getOne = true)
	{
		$dbc = dbc(DBCMax)->select('push_template')->where(array($field=>$value));
		if ($getOne)
		{
			$dbc->limit(1);
		}
		return $dbc->done();
	}
	public function Update($id, $data)
	{
		dbc()->SetTable(table('push_template'));
		$data['update'] = time();
		if ($id == 0)
		{
			$result = dbc()->Insert($data);
		}
		else
		{
			$result = dbc()->Update($data, 'id='.$id);
		}
		return $result;
	}
	public function Del($id)
	{
		dbc()->SetTable(table('push_template'));
		dbc()->Delete('', 'id='.$id);
	}
}


/**
 * 扩充类：数据查询
 * @author Moyo <dev@uuland.org>
 */
class PushLogic_Query
{
	private $queryTable = 'queue';
	public function from($table)
	{
		$this->queryTable = 'push_'.$table;
		return $this;
	}
	public function where($where, $limit = 1)
	{
		return dbc(DBCMax)->select($this->queryTable)->where($where)->limit($limit)->done();
	}
	public function delete($where)
	{
		return dbc(DBCMax)->delete($this->queryTable)->where($where)->done();
	}
}

?>