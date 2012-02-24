<?php

/**
 * 模块：推送信息管理
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package module
 * @name push.mod.php
 * @version 1.0
 */

class ModuleObject extends MasterObject
{
    public function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    public function Main()
    {
        $this->Queue();
    }
    public function Queue()
    {
        $rund = get('rund', 'txt');
        if (!$rund) $rund = 'false';
        $list = logic('push')->ListQueue($rund);
        include handler('template')->file('@admin/push_queue');
    }
    public function Queue_clear()
    {
        $rund = get('rund', 'txt');
        $sql_limit_time = '`update` '.$this->__sql_clear_time();
        $sql = 'DELETE FROM '.table('push_queue').' WHERE '.$sql_limit_time.' AND rund="'.$rund.'"';
        dbc()->Query($sql);
        $this->Messager('操作完成！', '?mod=push&code=queue&rund='.$rund);
    }
    public function Log()
    {
        $type = get('type');
        $qType = $type ? $type : null;
        $list = logic('push')->ListLog($qType);
        include handler('template')->file('@admin/push_log');
    }
    public function Log_clear()
    {
                $_POST['clear_time'] = 7;
        $_POST['clear_unit'] = 'd';
        $_POST['clear_type'] = 'out';
        $sql_limit_time = 'type="mail" AND `update` '.$this->__sql_clear_time();
        $sql = 'DELETE FROM '.table('push_log').' WHERE '.$sql_limit_time;
        dbc()->Query($sql);
        $this->Messager('操作完成！', '?mod=push&code=log');
    }
    public function Manage_preview()
    {
        $table = get('table', 'text');
        $id = get('id', 'int');
        $push = logic('push')->query()->from($table)->where('id='.$id);
        $data = logic('push')->datapas($push['data'], 'de');
        exit($data['content']);
    }
    public function Manage_delete()
    {
        $table = get('table', 'text');
        $id = get('id', 'int');
        logic('push')->query()->from($table)->delete('id='.$id);
        exit('ok');
    }
    public function Manage_resend()
    {
        $table = get('table', 'text');
        $id = get('id', 'int');
        $push = logic('push')->query()->from($table)->where('id='.$id);
        if ($push['target'] == 'Broadcast')
        {
            exit('对不起，此条内容为群发模式，不可以进行重发！');
        }
        $data = logic('push')->datapas($push['data'], 'run');
        include handler('template')->file('@admin/push_resend');
    }
    public function Manage_resend_done()
    {
        $table = get('table', 'text');
        $id = get('id', 'int');
        $push_old = logic('push')->query()->from($table)->where('id='.$id);
        $data_old = logic('push')->datapas($push_old['data'], 'de');
        $data = array('content'=>post('content')?post('content', 'text'):addslashes($data_old['content']));
        if ($push_old['type'] == 'mail')
        {
            $data['subject'] = addslashes($data_old['subject']);
        }
        $type = $push_old['type'];
        $target = post('target')?post('target', 'text'):$push_old['target'];
        logic('push')->add($type, $target, $data, 7);
        exit('重发请求已经写入队列，您现在可以关闭此窗口了！');
    }
    private function __sql_clear_time()
    {
        $time = post('clear_time', 'int');
        $unit = post('clear_unit', 'txt');
        $type = post('clear_type', 'txt');
        $time_unit = array(
            's' => 1,
            'm' => 60,
            'h' => 3600,
            'd' => 86400
        );
        $now = time();
        $pox = $now - $time * $time_unit[$unit];
        if ($type == 'in')
        {
            return '>= '.$pox;
        }
        else
        {
            return '<= '.$pox;
        }
    }
    
}

?>