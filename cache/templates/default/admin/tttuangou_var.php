<? include handler('template')->file('@admin/header'); ?>
 <form action="<?=$action?>" method="post"  enctype="multipart/form-data">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder asterisk"> <tr class="header"> <td colspan="2">常用设置</td> </tr> <tr> <td width="23%" bgcolor="#F4F8FC">默认团购成功数量：</td> <td width="77%" align="right"><label> <input name="default_successnum" type="text" id="default_successnum" size="30" value="<?=$product['default_successnum']?>">&nbsp;&nbsp;<i>*</i>设置默认多少人购买才算成功的团购，添加产品时可修改
</label></td> </tr> <tr> <td bgcolor="#F4F8FC">默认虚拟购买人数：</td> <td align="right"><label> <input name="default_virtualnum" type="text" id="default_virtualnum" size="30" value="<?=$product['default_virtualnum']?>">&nbsp;&nbsp;<i>*</i>设置默认虚拟购买人数
</label></td> </tr> <tr> <td bgcolor="#F4F8FC">默认团购一次<b>最多</b>购买数量：</td> <td align="right"><label> <input name="default_oncemax" type="text" id="default_oncemax" size="30" value="<?=$product['default_oncemax']?>" />&nbsp;&nbsp;<i>*</i>一个人在不超过总数的情况下一次性最多可以买多少产品!
</label></td> </tr> <tr> <td bgcolor="#F4F8FC">默认团购一次<b>最少</b>购买数量：</td> <td align="right"><label> <input name="default_oncemin" type="text" id="default_oncemin" size="30" value="<?=$product['default_oncemin']?>" />&nbsp;&nbsp;<i>*</i>用户需要购买多少个以上才允许参团
</label></td> </tr> <tr> <td bgcolor="#F4F8FC">邀请好友奖励金额（元）：</td> <td align="right"><input name="default_payfinder" type="text" id="default_payfinder" size="6" value="<?=$product['default_payfinder']?>" />&nbsp;&nbsp;<A HREF="admin.php?mod=tttuangou&code=mainfinder"><font color=blue><i>*</i>团购成功后，在返利管理中确认返利</font></A></td> </tr><tr> <td bgcolor="#F4F8FC">是否需要邮箱认证才能登陆：</td> <td align="right"><select name="default_emailcheck"> <option value="1" 
<? if($product['default_emailcheck']==1 ) { ?>
 selected
<? } ?>
>是</option> <option value="0" 
<? if($product['default_emailcheck']==0 ) { ?>
 selected
<? } ?>
>否</option> </select>&nbsp;&nbsp;<i>*</i>不建议开启，因为有些邮箱可能收不到邮件，影响团购交易。
</td> </tr> <tr> <td bgcolor="#F4F8FC">google地图接口密钥：</td> <td align="right"><input name="default_googlemapkey" type="text" id="default_googlemapkey" size="90" value="<?=$product['default_googlemapkey']?>" /> <BR />设置密钥后，可设置商家的地图位置，方便团购用户查看。<a href="http://code.google.com/intl/zh-CN/apis/maps/signup.html" target="_blank">[<font color=blue>点此免费申请</font>]</a></td> </tr> </table> <br> <center><input type="submit" class="button" name="addsubmit" value="提 交"></center> </form>
<? include handler('template')->file('@admin/footer'); ?>