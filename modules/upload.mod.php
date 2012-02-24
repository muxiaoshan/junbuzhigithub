<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename upload.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:51 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{

    function ModuleObject( $config )
    {
        $this->MasterObject($config);
                $rid = user()->get('role_id');
        $rid || $rid = 1;
        $arids = explode(',', ini('upload.role'));
        if (false === array_search($rid, $arids))
        {
            $msg = 'Access Deined';
			if ($this->Code == 'image')
			{
				$ops = array(
					'status' => 'fails',
					'msg' => $msg
				);
			}
			elseif ($this->Code == 'editor')
			{
				$ops = array(
					'error' => 1,
					'message' => $msg
				);
			}
			else
			{
				exit($msg);
			}
			exit(jsonEncode($ops));
        }
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    function Main()
    {
        exit('IO.Uploads.Index');
    }
    function Image()
    {
                $result = logic('upload')->Save('Filedata');
        if (isset($result['error']) && $result['error'])
        {
            $ops = array(
                'status' => 'fails',
                'msg' => $result['msg']
            );
        }
        else
        {
            $ops = array(
                'status' => 'ok',
                'file' => $result
            );
        }
        exit(jsonEncode($ops));
    }
    function Editor()
    {
        $field = get('field', 'txt');
        $result = logic('upload')->Save($field);
        if (isset($result['error']) && $result['error'])
        {
            $ops = array(
                'error' => 1,
                'message' => $result['msg']
            );
        }
        else
        {
            $ops = array(
                'error' => 0,
                'url' => $result['url']
            );
        }
        exit(jsonEncode($ops));
    }
    function Iframe()
    {
    	$field = get('field', 'txt');
        $result = logic('upload')->Save($field);
        if (isset($result['error']) && $result['error'])
        {
            $ops = array(
                'status' => 'fails',
                'msg' => $result['msg']
            );
        }
        else
        {
            $ops = array(
                'status' => 'ok',
                'file' => $result
            );
        }
        exit('<script type="text/javascript">window.parent.ups_Result('.jsonEncode($ops).');</script>');
    }
    function UI()
    {
    	$driver = get('driver', 'txt');
    	include handler('template')->file('@html/uploader/image_ui_'.$driver);
    }
}

?>