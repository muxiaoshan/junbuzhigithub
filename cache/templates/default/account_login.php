<? include handler('template')->file('header'); ?>
<div class="m960">
<form method="POST"  action="<?=$action?>">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<div class="t_l">
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title" >用户登陆</p>
<div class="sect"  >
<div class="nleftL l_nleftL" >
<div class="field">
<label>用户名</label>
<input name="username" type="text"  class="f_input l_input"/>
</div>
<div class="field">
<label>密　码</label>
<input name="password" type="password" class="f_input l_input" />
<span class="lostpassword"><a href="?mod=get_password">忘记密码？</a></span>
</div>
<div class="field">
<input name="keeplogin" type="checkbox" checked="checked" id="check_remember" /><label id="remember" for="check_remember">记住登录状态</label>
</div>
<div class="clear"></div>
<div class="act" id="l_act">
<input type="submit" class="formbutton formbutton_invite"  value="登 录">
</div>
</div>
<div class="login_alipay">
<?=account('ulogin')->wlist()?>
</div>
</div>
</div>
</div>
<div class="l_rc_b" ></div>
</div>
<div class="t_r">
<div class="r_m_rc_t"></div>
<div class="t_area_out rc_t_area_out">
<h1>还没有本站帐户？</h1>
<div class="t_area_in">
<p><a href="?mod=account&code=register">立即注册</a>，仅需30秒！</p>
</div>
</div>
<div class="r_m_rc_b"></div>
<?=ui('widget')->load()?>
</div>
</form>
</div>
<? include handler('template')->file('footer'); ?>