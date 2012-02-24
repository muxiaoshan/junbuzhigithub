<?php
/**
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package php
 * @name howdom.function.php
 * @date 2011-12-07 13:42:08
 */
 




function ad_config_save_parser_howdom($data)
{
	if (count($data['list']) < 1) return;
	foreach ($data['list'] as $id => $cfg)
	{
		$fid = 'file_'.$id;
		if (isset($_FILES[$fid]) && is_array($_FILES[$fid]))
		{
			logic('upload')->Save($fid, ROOT_PATH.$data['list'][$id]['image']);
		}
	}
}

?>
