<? $kf=ini('data.cservice') ?>
<div class="r_m_rc_t"></div>
<div class="t_area_out rc_t_area_out">
<h1>在线客服</h1>
<div class="t_area_in">
<ul class="kefu_list" >
<? if($kf['qq'] != '') { ?>
<? $qqs = explode(',', $kf['qq']) ?>
<li class="kefu_qq">
<? if(is_array($qqs)) { foreach($qqs as $qq) { ?>
<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?=$qq?>&site=qq&menu=yes" title="点击这里给我发消息"><img src="http://wpa.qq.com/pa?p=2:<?=$qq?>:41" /></a>
<? } } ?>
</li>
<? } ?>

<? if($kf['msn'] != '') { ?>
<li class="kefu_msn">MSN：<?=$kf['msn']?></li>
<? } ?>

<? if($kf['aliww'] != '') { ?>
<li class="kefu_aliww">阿里旺旺：<a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?=$kf['aliww']?>&site=cnalichn&s=4" title="点击这里给我发消息"><?=$kf['aliww']?></a></li>
<? } ?>

<? if($kf['phone'] != '') { ?>
<li class="kefu_phone">电话：<?=$kf['phone']?></li>
<? } ?>
</ul>
</div>
</div>
<div class="r_m_rc_b"></div>