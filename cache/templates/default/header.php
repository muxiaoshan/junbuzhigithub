<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?='<base h'.'ref="'.ini('settings.site_url').'/" />'?>
<meta http-equiv="Content-Type" content="text/html; charset=<?=ini("settings.charset")?>" />
<title><? echo ($this->Title != '') ? $this->Title.' - ' : ''; ?><?=ini("settings.site_name")?><?=$this->Config['page_title']?></title>
<meta name="Keywords" content="<?=ini('settings.'.(mocod()=='index.main'?'index_':'').'meta_keywords')?>" />
<meta name="Description" content="<?=ini('settings.'.(mocod()=='index.main'?'index_':'').'meta_description')?>" />
<script type="text/javascript">
var thisSiteURL = '<?=ini("settings.site_url")?>/';
</script>
<link rel="shortcut icon" href="favicon.ico" />
<?=ui('loader')->css('main')?>
<?=ui('loader')->js('@jquery')?>
<?=ui('loader')->js('@common')?><? echo '<!-'.'-[if IE 6]>'; ?><?=ui('loader')->js('DD_belatedPNG')?>
<script type="text/javascript">
DD_belatedPNG.fix('*');
</script><? echo '<![endif]-'.'->'; ?><script type="text/javascript">
$(document).ready(function(){
if ($(".m960").height()<400)
{
$(".m960").height(400);
}
});
</script>
<? if(false==DEBUG) { ?>
<?=ui('loader')->js('@error.clear')?>
<? } ?>
</head>
<body>
<div class="m_bg">
<a name="htop" id="htop"></a>
<div class="header" style=" position:relative; z-index:9999;">
<div class="h960">
<div class="at_logo"> 
<div class="vcoupon">
<a href="?mod=list&code=ckticket">&raquo; 团购券验证及消费登记</a>
</div>
<div class="logo"><a href="./"><img src="templates/default/images/zhanweifu.gif" /></a></div>
<a class="at_city" href="javascript:void(0)"><?=logic('misc')->City('name')?></a>
<div id="change_city" > <span id="top_title">切换城市</span>
<div id="show_provinces" >
<div class="city_close" id="close">[关闭]</div>
<div style="margin-bottom:0.75em; font-weight:bolder;" class="city_chose"><span>请选择您所在的城市:</span></div>
<ul class="scity">
<? if(is_array(logic('misc')->CityList())) { foreach(logic('misc')->CityList() as $i => $value) { ?>
<li><span class="sub_title"><a href="?city=<?=$value['shorthand']?>"><?=$value['cityname']?></a></span></li>
<? } } ?>
</ul>
</div>
</div>
<? $unms = ui('style')->allowMulti() ? false : true ?>
<div id="Tpl_c"
<? if($unms) { ?>
 style="background:none;"
<? } ?>
>
<a class="ft_top" href="?mod=subscribe&code=mail"
<? if($unms) { ?>
 style="margin:0;"
<? } ?>
>邮件订阅</a>
<a class="ft_top sms" href="?mod=subscribe&code=sms"
<? if($unms) { ?>
 style="margin:0 0 0 -100px;"
<? } ?>
>短信订阅</a>
<span id="page_options" onclick="ShowHideDiv()"
<? if($unms) { ?>
 style="display:none;"
<? } ?>
>更换界面<div id="Sright"><script type="text/javascript">writeCSSLinks();</script></div></span> 
</div>
<?=ui('style')->loadCSS()?>
</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
$(".menu_tq").mouseover(function(){$(".menu_tqb").show();$(".menu_tq").addClass("menu_tqs");});
$(".menu_tq").mouseout(function(){$(".menu_tqb").hide();$(".menu_tq").removeClass("menu_tqs");});
});
</script>
<div class="t_nav t_nav1">
<div class="h960">
<div class="sign" >
<? if(MEMBER_ID > 0) { ?>
<script type="text/javascript">
$(document).ready(function(){
if ($(".sp_member").width()>75){
$(".sp_member").width(75) ;    }
});
</script>
<div class="menu" style=" margin-left:5px;"><em><span style="float:left;">欢迎您，</span><span class="sp_member" style="max-width:75px; height:40px; overflow:hidden; display:block; float:left; "><?=MEMBER_NAME?></span>！<a href="?mod=account&code=logout">退出</a></em></div>
<? if(MEMBER_ROLE_ID==2) { ?>
<div class="menu" style=" margin-left:5px;"><a href="admin.php">管理后台</a></div>
<? } ?>
 
<? if(MEMBER_ROLE_ID==6) { ?>
<div class="menu" style=" margin-left:5px;"><a href="?mod=seller">商家管理</a></div>
<? } ?>
 
<div class="menu"> <div class="menu_tq">我的团购<div class="menu_tqb"><a href="?mod=me&code=coupon" class="sh_t">我的团购券</a><a href="?mod=me&code=order">我的订单</a><a href="?mod=me&code=bill">消费详单</a><a href="?mod=me&code=setting">账户设置</a><a href="?mod=me&code=address">收货地址</a><a href="?mod=recharge">账户充值</a></div></div></div>
<? } else { ?><div class="menu" style=" margin-left:5px;">
您好！欢迎您的到来。<a href="?mod=account&code=login">登录</a> | <a href="?mod=account&code=register">注册</a>
</div>
<? } ?>
</div>
<div class="headernav_list">
<? if(is_array($this->Config['__navs'])) { foreach($this->Config['__navs'] as $i => $nav) { ?>
<a href="<?=$nav['url']?>" title="<?=$nav['title']?>" target="<?=$nav['target']?>" class="<?=$nav['class']?>"><span><?=$nav['name']?></span></a>
<? } } ?>
</div>
</div>
</div>
<? echo ui('loader')->css($this->Module.'.'.$this->Code) ?>