<? include handler('template')->file('header'); ?>
<div class="m960">
<div class="t_l" >
<div class="l_rc_t" ></div>
<div class="t_area_out rc_t_area_out">
<div class="t_area_in">
<p class="cur_title ttwd">在线问答</p>
<ul class="consult_list" >
<? if(is_array(logic('misc')->AskList())) { foreach(logic('misc')->AskList() as $i => $value) { ?>
<li>
<div class="item"><a name="id<?=$value['id']?>" id="id<?=$value['id']?>"></a>
<p class="user"><strong><?=$value['username']?></strong><span>
<? echo date('Y-m-d',$value['time']) ?>
</span></p>
<div class="clear"></div>
<p class="text"><?=$value['content']?></p>
<p class="reply"><strong>回复：</strong><?=$value['reply']?></p>
</div>
</li>
<? } } ?>
</ul>
<ul class="paginator">
<?=page_moyo()?>
</ul>
<p class="cur_title manzuo_ask">? 我要提问<a name="q_form" id="q_form"></a></p>
<? if(MEMBER_ID<=0) { ?>
<div class="sect">你还没有登录，请 <a href="?mod=account&code=register">注册</a> 或 <a href="?mod=account&code=login">登录</a></div>
<? } else { ?><div class="sect"  ><?=MEMBER_NAME?>:你已经成功登录，欢迎您发表提问！</div>
<? } ?>
<div class="sect consult" >
<form action="<?=$action?>" enctype="multipart/form-data" method="post" >
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/>
<textarea name="question" rows="5" cols="80" class="f-textarea"></textarea>
<p class="commit">
<input type="submit" class="formbutton formbutton_ask" name="commit" value="好了，提交">
</p>
</form>
</div>
</div>
</div>
<div class="l_rc_b"></div>
</div>
<div class="t_r">
<?=ui('widget')->load()?>
</div>
</div>
<? include handler('template')->file('footer'); ?>