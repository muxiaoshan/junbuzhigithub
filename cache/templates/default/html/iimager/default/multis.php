<?=ui('loader')->css('@jquery.smallslider')?>
<?=ui('loader')->js('@jquery.smallslider')?>
<div class="imgs_displayer">
<div class="window">
<div id="imageslider" class="smallslider">
<ul>
<? if(is_array($iids)) { foreach($iids as $i => $iid) { ?>
<li><a href="javascript:void();"><img src="<? echo imager($iid, IMG_Normal); ?>" alt="<? echo logic('upload')->Field($iid, 'intro'); ?>" /></a></li>
<? } } ?>
</ul>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
$('#imageslider').smallslider({
onImageStop: false,
switchEffect: 'ease',
switchEase: 'easeOutSine',
switchPath: 'left',
switchMode: 'hover',
textSwitch: 2,
textPosition: 'bottom',
textAlign: 'left'
});
});
</script>