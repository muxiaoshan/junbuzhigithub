<?php

/**
 * 界面支持：挂件
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package UserInterface
 * @name widget.ui.php
 * @version 1.0
 */

class WidgetUI
{
	function load( $area = '' )
	{
		if ($area == '')
		{
			$area = str_replace('.', '_', mocod());
		}
		$pox = 'widget.'.$area.'.blocks';
		$list = ini($pox);
		if ( false === $list )
		{
			ini('widget.~@config.listener.enabled') && ini($pox, array());
			return;
		}
		echo '<!'.'-- widget @ [ '.$area.' ] --'.'>';
		foreach ( $list as $name => $one )
		{
			if (isset($one['enabled']) && $one['enabled'])
			{
				handler('template')->load('@widget/' . $name);
			}
		}
	}
}

?>