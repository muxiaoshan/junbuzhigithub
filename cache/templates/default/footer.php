<div class="footer" >
<div class="ft_bg"></div>
<script type="text/javascript">
$(document).ready(function() {
$("#top_title").click(function() {
$("#show_provinces").toggle();
}).css({ "cursor":"pointer" , "text-decoration":"underline" });	
var last_show = null;
$(".sub_title").mouseover(function() {
if(last_show != null) {
last_show.prev().css({ "color":"#FF6600" , "font-weight":"normal" });
last_show.hide();				
}
last_show = $(this).css({ "color":"#FF6600" }).next();
last_show.show();	
}).css({ "cursor":"pointer" , "text-decoration":"underline" });	
$("li a").click(function() {			
$(".show_citys").hide();
$("#show_provinces").hide();
});
$("#close").click(function() {
$(".show_citys").css({ "color":"#FF6600" }).hide();
$("#show_provinces").css({ "color":"#FF6600" }).hide();
}).mouseover(function() { 
$(this).css({ "color":"#FF6600" });
}).mouseout(function() {
$(this).css({ "color":"#FF6600" });	
}).css({ "cursor":"pointer" });
});
</script>
<div id="ft">
<ul class="cf" style=" width:960px; position:relative;">
<li class="col" style="position:absolute; overflow:visible; left:0; background:none;">
<h3>商务合作</h3>
<ul class="sub-list">
<li><a href="?mod=list&code=feedback">意见反馈</a></li>
<li><a href="?mod=list&code=business">商务合作</a></li>
<li><a href="?mod=openapi">开放 API</a></li>
</ul>
</li>
<li class="col" style="margin-left:160px;">
<h3>如何团购</h3>
<ul class="sub-list">
<li><a href="?mod=html&code=help">团购指南</a></li>
<li><a href="?mod=html&code=faq">常见问题</a></li>
<li><a href="?mod=subscribe&code=mail">邮件订阅</a></li>
<li><a href="?mod=subscribe&code=sms">短信订阅</a></li>
</ul>
</li>
<li class="col">
<h3>联系我们</h3>
<ul class="sub-list">
<li><a href="?mod=html&code=contact">联系我们</a></li>
<li><a href="?mod=list&code=ask">在线问答</a></li>
</ul>
</li>
<li class="col">
<h3>公司信息</h3>
<ul class="sub-list">
<li><a href="?mod=html&code=about">关于我们</a></li>
<li><a href="?mod=html&code=privacy">隐私保护</a></li>
<li><a href="?mod=html&code=join">加入我们</a></li>
<li><a href="?mod=html&code=terms">用户协议</a></li>
</ul>
</li>
<li class="col end" >
<div class="logo-footer"><div id="logo-footer"> <a href="./"><img src="templates/default/images/zhanweifu.gif" /></a></div>
<p style=" text-align:center;"><a href="http://www.miibeian.gov.cn/" target="_blank" title="网站备案"><?=$this->Config['icp']?></a><?=$this->Config['tongji']?>&nbsp; <?=$this->Config['copyright']?>&nbsp;
</p>
</div>
</li>
</ul>
</div>
<div class="attestation">
<a href="#"><img src="templates/default/images/trust_r1_c3.gif" /></a>
<a href="#"><img src="templates/default/images/trust_r1_c1.gif" /></a>
<a href="#"><img src="templates/default/images/trust_r1_c7.gif" /></a>
<?=logic('acl')->Comlic()?>
</div>
</div>
<div class="friend_link"
<? if(count(ini('link'))==0) { ?>
 style="display:none;"
<? } ?>
>
<div class="link">
<h3>友情链接：</h3>
<ul class="sub-list" >
<? if(ini('link')) { ?>
<? if(is_array(ini('link'))) { foreach(ini('link') as $i => $value) { ?>
<li>
<a href="<?=$value['url']?>" title="<?=$value['name']?>" target="_blank">
<? if($value['logo']) { ?>
<img src="<?=$value['logo']?>" style="width:81px;" height="31px" />
<? } else { ?><?=$value['name']?>
<? } ?>
</a>
</li>
<? } } ?>
<? } ?>
</ul>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
$('.goTop').click(function(e){
e.stopPropagation();
$('html, body').animate({scrollTop: 0},300);
backTop();
return false;
});
});
</script>
<div id="backtop" class="backTop"><a href="/#" class="goTop" title="返回顶部"></a></div>
<script type="text/javascript">
window.onscroll=backTop;
function backTop(){
var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
if(scrollTop==0){
document.getElementById('backtop').style.display="none";
}else{
document.getElementById('backtop').style.display="block";
}
}
backTop();
</script>
<? echo ui('loader')->js('@'.$this->Module.'.'.$this->Code) ?>
<?=ui('loader')->js('@footer')?>
<?=ui('pingfore')->html()?>
<? $this->ob_gzhandler() ?>
</body>
</html>
<?=handler('member')->UpdateSessions()?>