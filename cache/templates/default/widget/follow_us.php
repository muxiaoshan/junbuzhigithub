<? $wl=ini('data.follow_us');
$maps = array(
'sina' => array('name' => '新浪微博', 'url' => 'weibo.com/'),
'qq' => array('name' => '腾讯微博', 'url' => 't.qq.com/'),
'163' => array('name' => '网易微博', 'url' => 't.163.com/'),
'sohu' => array('name' => '搜狐微博', 'url' => 't.sohu.com/u/'),
);
 ?>
<div class="r_m_rc_t"></div>
<div class="t_area_out rc_t_area_out">
<h1>在这里关注我们</h1>
<div class="t_area_in">
<? if(is_array($wl)) { foreach($wl as $flag => $uid) { ?>
<? if($uid != '') { ?>
<a href="http://<?=$maps[$flag]['url']?><?=$uid?>" target="_blank" title="<?=$maps[$flag]['name']?>">
<img src="templates/widget/images/follow_us/<?=$flag?>.gif" />
</a>
<? } ?>
<? } } ?>
</div>
</div>
<div class="r_m_rc_b"></div>