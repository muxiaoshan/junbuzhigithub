<? include handler('template')->file('@admin/header'); ?>
 <form method="post"  action="<?=$action?>">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <INPUT TYPE="hidden" name='uid' value=<?=$this->ID?>> <INPUT TYPE="hidden" name='old_username' value=<?=$username?>> <a name="<?=$this->Title?> - <?=$username?>"></a> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="2"><?=$this->Title?> - <?=$username?></td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>用户名:</b><br> <span class="smalltxt">如不是特别需要，请不要修改用户名</span></td> <td>
<? if($uid < 2) { ?>
 <input type="text" size="30" name="username" value="<?=$username?>" readonly> 
<? } else { ?> <input type="text" size="30" name="username" value="<?=$username?>"> 
<? } ?>
 </td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>账户余额:</b><BR> <span class="smalltxt">可通过充值、扣费来修改账户余额</span></td> <td> <input type="text" size="10" name="money" value="<?=$money?>" disabled readonly="true"/> <select name="moneyOps"> <option value="plus">充值</option> <option value="less">扣费</option> </select> <input type="text" size="10" name="moneyMoved" value="" />
元
</td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>新密码:</b><br> <span class="smalltxt">如果不更改密码此处请留空</span></td> <td><input type="text" size="30" name="password" value> </td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>用户角色:</b><br> <span class="smalltxt">用户在网站里属于哪种角色</span></td> <td> 
<? if($uid < 2) { ?>
<?=$role_name?>
<? } else { ?><?=$role_select?>
<? } ?>
 </td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>性别:</b></td> <td> <?=$gender_radio?></td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>Email:</b></td> <td><input type="text" size="30" name="email" value="<?=$email?>"> </td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>手机:</b></td> <td><input type="text" size="30" name="phone" value="<?=$phone?>"> </td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>QQ:</b></td> <td><input type="text" size="30" name="qq" value="<?=$qq?>"> </td> </tr> <tr> <td width="40%" bgcolor="#F4F8FC"><b>注册 IP:</b></td> <td><input type="text" size="30" name="regip" value="<?=$regip?>"> </td> </tr> </table> <center> <input type="submit" class="abutton" name="editsubmit" value="提 交"> </center> </form> <br/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="4">该用户消费明细(余额：<?=$money?>元)</td> </tr> <tr class="tr_nav"> <td width="12%">项目</td> <td width="12%">金额</td> <td width="12%">时间</td> <td>详细</td> </tr>
<? if(is_array($log)) { foreach($log as $i => $value) { ?>
<tr
<? if($value['class']!='usr') { ?>
 style="background:#FF9999;" title="特殊资金异动"
<? } ?>
> <td><b><?=$value['name']?></b></td> <td><font style="font-size:12pt;font-weight:bold;color:
<? if($value['type']=='plus') { ?>
blue;">+
<? } else { ?>red;">-
<? } ?>
</font> <?=$value['money']?></td> <td>
<? echo date('Y-m-d H:i:s',$value['time']); ?>
</td> <td><?=$value['intro']?></td> </tr>
<? } } ?>
<tr> <td colspan="4"><?=page_moyo()?></td> </tr> </table>
<? include handler('template')->file('@admin/footer'); ?>