<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename isearcher.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 




class iSearcherUI
{
    
    public function load($idx)
    {
        $map = ini('isearcher.map');
        $fidString = ini('isearcher.idx.'.$idx);
        $fids = explode(',', $fidString);
        $filter = ini('isearcher.filter');
        $ffsString = ini('isearcher.frc.'.$idx);
        $frcs = explode(',', $ffsString);
        include handler('template')->file('@html/isearcher/index');
    }
}

?>