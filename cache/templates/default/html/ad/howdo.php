<div id="ad__howdo_helps" style="display:none;width:960px;height:153px;margin:10px auto;background:url(<?=$cfg['image']?>) no-repeat center;">
<div class="howdo_close" style="
<? if($cfg['close_allow']!='yes') { ?>
display:none;
<? } ?>
float:right;margin-top:0;margin-right:10px;padding-right:25px; padding-top:1px;background:url(templates/html/ad/images/howdo.close.gif) no-repeat 80% 3px;">
<a href="javascript:__ad_howdo_hidden();" style="color:#666; font-size:12px; text-decoration:none;">关闭</a>
</div>
<a href="<?=$cfg['linker']?>" style="display:block;width:951px;height:110px;"></a>
</div>
<script type="text/javascript">
// 购买帮助
$(document).ready(function(){
if (readCookie('ad_hh_vsd') != 'yes')
{
$('#ad__howdo_helps').show();
}
<? if($cfg['close_allow'] == 'yes' && $cfg['auto_hide_allow']) { ?>
setTimeout(function(){__ad_howdo_hidden()}, <? echo (int)$cfg['auto_hide_time']; ?>*1000);
<? } ?>
});
function __ad_howdo_hidden()
{
$('#ad__howdo_helps').slideUp();
writeCookie('ad_hh_vsd', 'yes', <? echo (int)$cfg['reshow_delay_time']; ?>);
}
</script>