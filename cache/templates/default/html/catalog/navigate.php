<?=ui('loader')->css('#html/catalog/style')?>
<div class="catalog">
<div class="catalog-nav">
分类：
</div>
<div class="catalog-data">
<? if(is_array($catalog)) { foreach($catalog as $i => $topclass) { ?>
<? if(logic('catalog')->urlTopClass && $topclass['id'] != logic('catalog')->urlTopClass) { ?>
<? continue ?>
<? } ?>
<div class="fq_this_bg_l"></div>
<div class="fq_this_bg_c"><a href="?mod=catalog&code=<?=$topclass['flag']?>" class="topclass-name"><?=$topclass['name']?></a></div>
<div class="fq_this_bg_r"></div>
<? if(is_array($topclass['subclass'])) { foreach($topclass['subclass'] as $ii => $subclass) { ?>
<a href="?mod=catalog&code=<?=$topclass['flag']?>_<?=$subclass['flag']?>" class="subclass-name"><?=$subclass['name']?><font class="subclass-count">(<?=$subclass['oslcount']?>)</font></a>
<? } } ?>
<br/>
<? } } ?>
</div>
<div style="clear: both;  height:0px; overflow:hidden;"></div>
</div>
<div style="display: none;">天天团购+分类导航</div>