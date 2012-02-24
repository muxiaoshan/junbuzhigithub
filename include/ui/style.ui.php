<?php

/**
 * 界面支持：前端界面风格
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package UserInterface
 * @name style.ui.php
 * @version 1.0
 */

class StyleControllerUI
{
    
    public function allowMulti()
    {
        $disable = ini('product.disabled_multi_style');
        return $disable ? false : true;
    }
    
    public function loadCSS()
    {
                $this->allowMulti() || $_COOKIE['stylesheet'] = ini('product.default_style');
                $style = $style_S = $_COOKIE['stylesheet'];
        $style || $style = ini('product.default_style');
        $urlBase = ini('settings.site_url');
                $cssMAPS = array(
            'styles0' => 'templates/default/styles/t1.css',
            'styles1' => 'templates/tpl_2/styles/t1.css',
            'styles2' => 'templates/tpl_3/styles/t1.css',
            'styles3' => 'templates/tpl_4/styles/t1.css',
            'styles4' => 'templates/tpl_5/styles/t1.css'
        );
        $html = '<link title="'.$style.'" href="'.$urlBase.'/'.$cssMAPS[$style].'" rel="stylesheet" type="text/css" />';
                if ($style != $style_S)
        {
                        setcookie('stylesheet', $style, time()+86400*365);
        }
        return $html;
    }
}

?>