<? include handler('template')->file('header'); ?>
<?=ui('loader')->css('@account.register')?>
<?=ui('loader')->js('@account.register')?>
<script type="text/javascript"> 
$(function(){ 
$("#email").focus(function(){$(this).css("background","#CBFE9F");$(".hint1").show();}).blur(function(){$(this).css("background","#FFF");$(    ".hint1").hide();});
$("#username").focus(function(){$(this).css("background","#CBFE9F");$(".hint2").show();}).blur(function(){$(this).css("background","#FFF");$(".hint2"    ).hide();});
$("#password").focus(function(){$(this).css("background","#CBFE9F");$(".hint3").show();}).blur(function(){$(this).css("background","#FFF");$(    ".hint3").hide();});
$("#phone").focus(function(){$(this).css("background","#CBFE9F");$(".hint4").show();}).blur(function(){$(this).css("background","#FFF");$(    ".hint4").hide();});
}); 
</script>
<style type="text/css">
.hint1, .hint2, .hint3, .hint4, .error {
background: #FFFFE3;
border: 1px solid #DCDCB3;
overflow: hidden;
padding: 1px 5px 2px;
width: 240px;
float: left;
margin-left: 0px;
display: none;
position: absolute;
color: #666;
font-size: 12px;
margin-top:2px;
}
#email_result,#username_result,#password_result,#phone_result{ position:absolute;}
</style>
<div class="m960">
<div class="t_l">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title">用户注册</p>
<div class="sect">
<form action="<?=$action?>" method="post"  enctype="multipart/form-data" >
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<div class="nleftL l_nleftL">
<div class="field">
<label>Email</label>
<input type="text" name="email" id="email"  class="f_input l_input" value="<?=$email?>" size="30">
<font id="email_result"></font>
<span class="hint1" style="display:none;">登录及找回密码用</span>
</div>
<div class="field">
<label>用户名</label>
<input type="text" name="truename" id="username" class="f_input l_input" size="30">
<font id="username_result"></font>
<span class="hint2" style="display:none;">4-16 个字符，一个汉字为两个字符</span>
</div> 
<div class="field">
<label>密码</label>
<input name="pwd" type="password" class="f_input l_input" id="password" size="30">
<font id="password_result"></font>
<span class="hint3" style="display:none;">最少 4 个字符</span>
</div>
<div class="field">
<label>确认密码</label>
<input name="ckpwd" type="password" class="f_input l_input" id="repassword" size="30">
<font id="repassword_result"></font>
</div>
<div class="field">
<label>手机号码</label>
<input type="text" name="phone" id="phone" class="f_input l_input"size="30">
<font id="phone_result"></font>
<span class="hint4" style="display:none;">请填写您的手机号码，团购券会通过手机发送</span> 
</div> 
<div class="field">
<label>所在城市</label>
<select name="city" class="f_product" id="city" >
<? if(is_array($city)) { foreach($city as $i => $value) { ?>
<option  value="<?=$value['cityid']?>"><?=$value['cityname']?></option>
<? } } ?>
<option value="0">其他</option>
</select>
</div>
<div class="field autologin">
<input name="showemail" type="checkbox" class="f_check" id="showemail" value="1" checked="checked" style=" float:left; width:20px;" >
<span style="*position:relative;*top:5px;">		订阅每日最新团购信息 </span>
</div>
<div class="clear"></div>
<div class="act" id="l_act">
<input type="submit" class="formbutton formbutton_invite"  value="注 册" >
</div>
</div>
</form>
</div>
</div>
</div>
<div class="l_rc_b" ></div>
</div>
<div class="t_r">
<div class="r_m_rc_t"></div>
<div class="t_area_out rc_t_area_out">
<h1>已有本站帐户？</h1>
<div class="t_area_in">
<p>请直接 <a href="?mod=account&code=login">登录</a>。</p>
<p><?=account('ulogin')->wlist()?></p>
</div>
</div>
<div class="r_m_rc_b"></div>
<?=ui('widget')->load()?>
</div>
</div>
<? include handler('template')->file('footer'); ?>