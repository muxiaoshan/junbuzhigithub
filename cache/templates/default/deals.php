<? include handler('template')->file('header'); ?>
<div class="m960" >
<div class="t_l" style=" position:relative;">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out" style=" width:713px;overflow:hidden;">
<div class="t_area_in list_s ">
<p class="cur_title manzuo_s" >往期团购</p>
<ul class="deal_list manzuo_s_1">
<? if(is_array($product)) { foreach($product as $i => $value) { ?>
<li>
<div class="pic2"> <a target="_blank" href="?view=<?=$value['id']?>"><img src="<? echo imager($value['imgs']['0'], IMG_Small); ?>" width="160" height="115" title="<? echo logic('upload')->Field($value['imgs']['0'], 'intro'); ?>" /></a> </div>
<div class="listInfo">
<p><a target="_blank" href="?view=<?=$value['id']?>" title="<?=$value['name']?>"><?=$value['flag']?></a></p>
<p style="background:#F3F3F3; width:380px; padding:5px;">
<? if($value['type'] == 'prize') { ?>
抽奖人数：<? echo logic('prize')->allCount($value['id']); ?>
<? } else { ?><span style="float:left"><i><?=$value['succ_buyers']?></i>人购买</span> <span style="float: left">&nbsp;&nbsp;&nbsp;售出&nbsp;&nbsp;<i><?=$value['sells_count']?></i>份</span> <span style="float:right; margin-right:10px;">共为用户节省 <font class="scMoney">&yen;
<? echo ($value['price']-$value['nowprice'])*$value['sells_count']; ?>
</font></span>
<? } ?>
</p>
<p>
原价：<em>&yen;<?=$value['price']?></em>
现价：<em style="color:#CC3333">&yen; <?=$value['nowprice']?></em>
折扣：<em><?=$value['discount']?>折</em>
节省：<em>&yen;
<? echo ($value['price']-$value['nowprice']) ?>
</em>
</p>
</div>
<div class="time_normal"><p class="time_time"><? echo date('Y-m-d', $value['overtime']);; ?></p><p class="time_underway">已结束</p></div>
</li>
<? } } ?>
</ul>
<div>
<?=page_moyo()?>
</div>
</div>
</div>
<div class="l_rc_b" ></div>
</div>
<div class="t_r">
<?=ui('widget')->load()?>
</div>
</div>
<? include handler('template')->file('footer'); ?>