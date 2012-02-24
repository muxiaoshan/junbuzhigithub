<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename isearcher.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




class iSearcherLogic
{
    
    function Search($fid, $wd)
    {
        $map = ini('isearcher.map.'.$fid);
        if (!$map)
        {
            return $this->__AJax_NOT_Found();
        }
        $table = $map['table'];
        $field = $map['src'];
        $iwField = $map['idx'];
        $sql = 'SELECT '.$iwField.','.$field.' FROM '.table($table).' WHERE '.$field.' LIKE "%'.$wd.'%" LIMIT 0, 10';
        $query = dbc()->Query($sql);
        if (!$query)
        {
            return $this->__AJax_NOT_Found();
        }
        $result = $query->GetAll();
        if (count($result) == 0)
        {
            return $this->__AJax_NOT_Found();
        }
        $ops = array(
            'resultCount' => count($result)
        );
        foreach ($result as $i => $one)
        {
            $ops['results'][] = array(
                'title' => $one[$field],
                'key' => $map['key'],
                'val' => $one[$iwField]
            );
        }
        return $ops;
    }
    
    public function iiSearch($map, &$key, &$wd)
    {
        if ($key != 'wd')
        {
                        $wd = ' ='.$wd;
            return;
        }
                $key = $map['key'];
        $table = $map['table'];
        $field = $map['src'];
        $iwField = $map['idx'];
        $sql = 'SELECT '.$iwField.','.$field.' FROM '.table($table).' WHERE '.$field.' LIKE "%'.$wd.'%" LIMIT 0, 10';
        $query = dbc()->Query($sql);
        if (!$query)
        {
            $key = '__404__';
            return;
        }
        $ids = $query->GetAll();
        if (count($ids) == 0)
        {
            $key = '__404__';
            return;
        }
        $idx = '';
        foreach ($ids as $i => $id)
        {
            $idx .= $id[$iwField].',';
        }
        $idx = substr($idx, 0, -1);
        $wd = ' IN('.$idx.')';
    }
    
    private function __AJax_NOT_Found()
    {
        return array(
            'resultCount' => 0,
            'msg' => __('没有找到相关内容！'),
        );
    }
    
    public function Linker(&$sql)
    {
        $swd = get('search');
        if (!$swd) return;
        list($key, $wd) = explode(':', $swd);
        if($key == '' || $wd == '') return;
        $ssrc = get('ssrc');
        if (!$ssrc) return;
        $mocod = mocod();
        $parser = '_lnk_of_'.str_replace('.', '_', $mocod);
        $map = ini('isearcher.map.'.$ssrc);
        $this->$parser($sql, $key, $wd, $map);
    }
    
    private function _lnk_of_product_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 'p.id'.$wd;
                break;
            case 'sid':
                $sql_where = 'p.sellerid'.$wd;
                break;
            case 'cid':
                $sql_where = 'p.city'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_order_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 'o.productid'.$wd;
                break;
            case 'oid':
                $sql_where = 'o.orderid'.$wd;
                break;
            case 'uid':
                $sql_where = 'o.userid'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_coupon_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 't.productid'.$wd;
                break;
            case 'oid':
                $sql_where = 't.orderid'.$wd;
                break;
            case 'uid':
                $sql_where = 't.uid'.$wd;
                break;
            case 'coid':
                $sql_where = 't.ticketid'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_delivery_vlist(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'pid':
                $sql_where = 'o.productid'.$wd;
                break;
            case 'oid':
                $sql_where = 'o.orderid'.$wd;
                break;
            case 'uid':
                $sql_where = 'o.userid'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
    private function _lnk_of_recharge_card(&$sql, $key, $wd, $map)
    {
        $this->iiSearch($map, $key, $wd);
        switch ($key)
        {
            case 'rcid':
                $sql_where = 'id'.$wd;
                break;
            default:
                $sql_where = '0';
        }
        $sql = str_replace('ORDER BY', ' AND '.$sql_where.' ORDER BY', $sql);
    }
}

?>