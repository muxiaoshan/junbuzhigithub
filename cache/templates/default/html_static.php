<? include handler('template')->file('header'); ?>
<div class="m960">
<div class="t_l">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title" style="width:650px;"><?=$html['title']?></p>
<div class="sect">
<?=$html['content']?>
</div>
</div>
</div>
<div class="l_rc_b"></div>
</div>
<div class="t_r"><? echo $html['name'] == '404' ? ui('widget')->load('html_404') : ui('widget')->load(); ?></div>
</div>
<? include handler('template')->file('footer'); ?>