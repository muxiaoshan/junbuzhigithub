<? include handler('template')->file('@admin/header'); ?>
 <script type="text/javascript">
function $$(ID)
{
return document.getElementById(ID);
}
</script> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td>使用提示</td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" style="font-size:14px;"> <div>1、请参考<a target="_blank" href="<?=ihelper('tg.help.uc')?>"><b style="color:red;">记事狗系统整合Ucenter的帮助</b></a></div> <div>2、操作前，请先备份好天天团购及Ucenter的数据库，以防数据丢失</div> <div>3、如果开启了本功能请，请正确的进行配置，否则会导致用户不能正常的注册、登录天天团购</div> <div style="color:red;">如进行了错误设置无法登陆，请通过ftp修改/setting/ucenter.php文件，将'enable' 变量设置为 '0'</div> </td> </tr> </table> <br> <form action="admin.php?mod=ucenter&code=do_setting" method="POST"  name="ucenterConfigForm">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <input type="hidden" name="ucenter[user_merge]" value="<?=$ucenter['user_merge']?>" /> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="2">Ucenter整合</td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
开启Ucenter？
</td> <td class="altbg2">
<?=$uc_enable_radio?>
</td> </tr> <tr bgcolor="#F8F8F8"> <td class="altbg1" width="30%"> <b>应用的 UCenter 配置信息:</b><br>
方法1、【推荐】直接从UCenter后台复制配置信息字符串填到右侧框，提交即可<br>
留空时不更新原先设置的信息<br>
方法2、<a href="#void" onclick="$$('#ucenter_config_diy').style.display='';return false;">点击此处手动设置</a> </td> <td class="altbg2"> <textarea name="uc_config_string" style="width:300px;height:80px;"></textarea> </td> </tr> </table> <table id="#ucenter_config_diy" style="display:none;"> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
连接方式
</td> <td class="altbg2"> <input type="radio" id="uc_connect_mysql" name="ucenter[uc_connect]" value="mysql" <?=$uc_connect_mysql_checked?> class=radio onclick="if($$('uc_connect_fsock').checked==true){$$('#ucenter_db_setting_body').style.display='none';}else{$$('#ucenter_db_setting_body').style.display='';}" /><label for="uc_connect_mysql">mysql</label>&nbsp;<input type="radio" id="uc_connect_fsock" name="ucenter[uc_connect]" value="fsock" <?=$uc_connect_fsock_checked?> class=radio onclick="if($$('uc_connect_fsock').checked==true){$$('#ucenter_db_setting_body').style.display='none';}else{$$('#ucenter_db_setting_body').style.display='block';}" /><label for="uc_connect_fsock">fsockopen 连接</label>&nbsp;
</td> </tr> <tbody id="#ucenter_db_setting_body" style="display:<?=$_style_display?>;"> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
Ucenter数据库地址
</td> <td class="altbg2"> <input name="ucenter[uc_db_host]" value="<?=$ucenter['uc_db_host']?>" /> </td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
Ucenter数据库用户
</td> <td class="altbg2"> <input name="ucenter[uc_db_user]" value="<?=$ucenter['uc_db_user']?>" /> </td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
Ucenter数据库密码
</td> <td class="altbg2"> <input type="password" name="ucenter[uc_db_password]" value="请输入Ucenter的数据库密码" /> </td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
Ucenter数据库名称
</td> <td class="altbg2"> <input name="ucenter[uc_db_name]" value="<?=$ucenter['uc_db_name']?>" /> </td> </tr> <tr bgcolor="#F4F8FC" style="display:none;"> <td class="altbg1" width="30%">
Ucenter数据库字符集
</td> <td class="altbg2"> <input name="ucenter[uc_db_charset]" value="<?=$ucenter['uc_db_charset']?>" /> </td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
Ucenter数据库表前辍
</td> <td class="altbg2"> <input name="ucenter[uc_db_table_prefix]" value="<?=$ucenter['uc_db_table_prefix']?>" /> </td> </tr> </tbody> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%"> <b>Ucenter通信密钥</b> </td> <td class="altbg2"> <input name="ucenter[uc_key]" value="<?=$ucenter['uc_key']?>" /> </td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%"> <b>Ucenter地址</b> </td> <td class="altbg2"> <input name="ucenter[uc_api]" value="<?=$ucenter['uc_api']?>" /> </td> </tr> <tr bgcolor="#F4F8FC" style="display:none;"> <td class="altbg1" width="30%">
Ucenter字符集
</td> <td class="altbg2"> <input name="ucenter[uc_charset]" value="<?=$ucenter['uc_charset']?>" /> </td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%">
Ucenter IP地址
</td> <td class="altbg2"> <input name="ucenter[uc_ip]" value="<?=$ucenter['uc_ip']?>" /> </td> </tr> <tr bgcolor="#F4F8FC"> <td class="altbg1" width="30%"> <b>当前应用的ID</b> </td> <td class="altbg2"> <input name="ucenter[uc_app_id]" value="<?=$ucenter['uc_app_id']?>" /> </td> </tr> </table> <br/> <center><button type="submit" class="button"> 提 交 </button></center> </form> <br/> 
<? include handler('template')->file('@admin/footer'); ?>