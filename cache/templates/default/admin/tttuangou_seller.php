<? include handler('template')->file('@admin/header'); ?>
 <form method="post"  action="<?=$action?>">
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="7"><b>商家管理</b></td> </tr>
<? if(!empty($seller)) { ?>
<tr> <td colspan="7"> <div class="tr_select">请输入您要搜索的商家关键词：
<input name="keyword" value="<?=$keyword?>" id="keyword" type="text"  class="isearcher_input_words"/> <select name="city" class="isearcher_select_list"> <option value="false" 
<? if($area=='false') { ?>
selected
<? } ?>
>不限城市</option>
<? if(is_array($city)) { foreach($city as $i => $value) { ?>
<option value="<?=$value['cityid']?>" 
<? if($area==$value['cityid']) { ?>
selected
<? } ?>
><?=$value['cityname']?></option>
<? } } ?>
</select> <input name="bottom" type="submit" id="bottom" value="搜索" class="isearcher_submit_button" /> </div> </td> </tr>
<? } ?>
<tr class="tr_nav"> <td width="20%">商家名称</td> <td width="8%">所在城市</td> <td width="13%">联系电话</td> <td width="8%">产品数</td> <td width="8%">成功订单</td> <td width="12%">销售额</td> <td width="8%" align="center">管理</td> </tr> 
<? if(empty($seller)) { ?>
 <tr><td colspan="7">暂时还没有商家，请<a href="?mod=tttuangou&code=addseller">点此添加商家</a>。</td></tr> 
<? } ?>
 
<? if(is_array($seller)) { foreach($seller as $i => $value) { ?>
 <tr onmouseover="this.className='tr_hover'" onmouseout="this.className='tr_normal'"> <td><?=$value['sellername']?><br/><a href="<?=$value['sellerurl']?>" target="_blank"><?=$value['sellerurl']?></a></td> <td><?=$newcity[$value['area']]?></td> <td><?=$value['sellerphone']?></td> <td><?=$value['productnum']?></td> <td><?=$value['successnum']?></td> <td><?=$value['money']?>元</td> <td align="center"><a href="?mod=tttuangou&code=editseller&id=<?=$value['userid']?>">修改</a> - <a href="#" title="点此删除该商家" onclick="if(confirm('您确认要删除该商家吗？')){window.location.href='?mod=tttuangou&code=deleteseller&id=<?=$value['id']?>'}">删</a></td> </tr> 
<? } } ?>
 <tr><td colspan="7">
请注意：添加商家时绑定的商家用户将成为商家产品团购券的管理者，<font color=red>商家用户可前台登陆、查看旗下团购券、核对和消费</font>！</td></tr> </table> <a href="?mod=tttuangou&code=addseller" class="back1 back2">添加商家</a> <br> <center>
<?=$page_arr?>
</center> </form>
<? include handler('template')->file('@admin/footer'); ?>