<?php

/**
 * 逻辑区：安全过滤
 * @copyright (C)2011 Cenwor Inc.
 * @author Moyo <dev@uuland.org>
 * @package logic
 * @name safe.logic.php
 * @version 1.0
 */

class SafeLogic
{
    private $__vf_driver = null;
    
    function Vars($method, $key, $limit)
    {
        switch ($method)
        {
            case 'POST':
                $var = &$_POST;
                break;
            default:
                $var = &$_GET;
        }
        if ($key == '')
        {
            return $var;
        }
        if ($limit == '')
        {
            $igc = isset($var[$key]) ? $var[$key] : false;
        }
        else
        {
            $igc = $var[$key];
            $loops = explode(';', $limit);
            foreach ($loops as $i => $rule)
            { 
                $igc = ($igc !== false) ? $this->__vars_filter($rule, $igc) : false;
            }
        }
        if (ENC_IS_GBK)
        {
            $IS_GET = ($_SERVER['REQUEST_METHOD'] == 'GET');
            if (is_string($igc) && ($IS_GET || X_IS_AJAX))
            {
                $igc = ENC_U2G($igc);
            }
        }
        return $igc;
    }
    private function __vars_filter($rule, $val)
    {
        if ($this->__vf_driver == null)
        {
            $this->__vf_driver = new SafeLogicVarsFilter();
        }
        if (method_exists($this->__vf_driver, $rule))
        {
            return $this->__vf_driver->$rule($val);
        }
        return $val;    
    }
}


class SafeLogicVarsFilter
{
    function int($val)
    {
        return (int)$val;
    }
    function number($val)
    {
        return is_numeric($val) ? $val : false;
    }
    function txt($val)
    {
        return htmlspecialchars($val);
    }
    function float($val)
    {
        return (float)$val;
    }
    function trim($val)
    {
    	if (is_array($val))
    	{
    		foreach ($val as $key => $one)
    		{
    			$val[$key] = trim($one);
    		}
    		return $val;
    	}
    	else
    	{
    		return trim($val);
    	}
    }
}
?>
