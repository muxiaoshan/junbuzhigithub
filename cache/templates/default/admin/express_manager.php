<? include handler('template')->file('@admin/header'); ?>
<?=ui('loader')->js('#admin/js/express.mgr')?>
<?=ui('loader')->css('#admin/css/express.mgr')?>
<form action="?mod=express&code=save" method="post" >
<input type="hidden" name="FORMHASH" value='<?=FORMHASH?>'/> <table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder"> <tr class="header"> <td colspan="4">配送方式<?=$actionName?></td> </tr> <tr> <td width="12%"></td> <td><input name="id" type="hidden" value="<?=$c['id']?>" /></td> </tr> <tr> <td>配送方式名称</td> <td> <input name="name" type="text" class="ilng" value="<?=$c['name']?>" /> </td> </tr> <tr> <td>所属快递公司</td> <td> <select name="express">
<? if(is_array($corpList)) { foreach($corpList as $one) { ?>
<option value="<?=$one['id']?>"
<? if($one['id']==$c['express']) { ?>
 selected="true"
<? } ?>
><?=$one['name']?></option>
<? } } ?>
</select> </td> </tr> <tr> <td>重量设置</td> <td>
首重单位<input name="firstunit" type="text" class="imin" value="<?=$c['firstunit']?>" /> <select name="fuunit"> <option value="g"
<? if($c['fuu']=='g') { ?>
 selected="true"
<? } ?>
>克</option> <option value="kg"
<? if($c['fuu']=='kg') { ?>
 selected="true"
<? } ?>
>千克</option> </select> <br/>
续重单位<input name="continueunit" type="text" class="imin" value="<?=$c['continueunit']?>" /> <select name="cuunit"> <option value="g"
<? if($c['cuu']=='g') { ?>
 selected="true"
<? } ?>
>克</option> <option value="kg"
<? if($c['cuu']=='kg') { ?>
 selected="true"
<? } ?>
>千克</option> </select> </td> </tr> <tr> <td>配送费用</td> <td>
首重费用<input name="firstprice" type="text" class="imin" value="<?=$c['firstprice']?>" /> <br/>
续重费用<input name="continueprice" type="text" class="imin" value="<?=$c['continueprice']?>" /> </td> </tr> <tr> <td>地区费用类型</td> <td> <label><input name="regiond" type="radio" class="iradio" value="0"
<? if($c['regiond']==0) { ?>
 checked="true"
<? } ?>
 />统一设置</label> <label><input name="regiond" type="radio" class="iradio" value="1"
<? if($c['regiond']==1) { ?>
 checked="true"
<? } ?>
 />指定收货地址和费用</label> </td> </tr> <tr class="regiond_no_area"> <td>默认费用</td> <td> <label><input name="dpenable" type="checkbox" value="true"
<? if($c['dpenable']=='true') { ?>
 checked="true"
<? } ?>
 /> 启用</label>
(不启用时，下列地区之外的买家将无法使用本配送方式)
</td> </tr> <tr class="regiond_no_area"> <td>支持的配送地区</td> <td> <div id="region_selector"> <select id="addr_province"></select> <select id="addr_city"></select> <select id="addr_country"></select> <a href="javascript:regionCommiter();">添加</a> <a href="javascript:regionSelector(0);">取消</a> </div> <ul id="regiond_list">
<? if(is_array($c['regions'])) { foreach($c['regions'] as $one) { ?>
<li id="ex_region_id_<?=$one['id']?>"> <div> <input name="ex_region_id[]" type="hidden" value="<?=$one['id']?>" /> <div class="float_right"> <a href="javascript:ex_regions_del('<?=$one['id']?>', <?=$one['id']?>);">删除</a> </div>
首重费用：<input name="ex_firstprice[]" type="text" class="imin" value="<?=$one['firstprice']?>" /> 续重费用：<input name="ex_continueprice[]" type="text" class="imin" value="<?=$one['continueprice']?>" /> <input id="erl_for_<?=$one['id']?>" name="ex_regions[]" type="hidden" class="ilng" value="<?=$one['region']?>" /><br/>
配送地区：
<br/>
<? if(is_array($one['regionName'])) { foreach($one['regionName'] as $i => $area) { ?>
<font id="erl_font_of_<?=$one['id']?>_<?=$i?>"><?=$area['name']?> | <a href="javascript:regionSelDel('<?=$one['id']?>', 'erl_font_of_<?=$one['id']?>_<?=$i?>', '[<?=$area['loc']?>]');">移除</a><br/></font>
<? } } ?>
<font id="erl_font_<?=$one['id']?>"></font> <br/> <a href="javascript:regionSelector('<?=$one['id']?>');">添加地区</a> </div> </li>
<? } } ?>
<li id="ex_region_tpl"> <div> <input name="ex_region_id[]" type="hidden" value="0" /> <div class="float_right"> <a href="javascript:ex_regions_del('[ID]', 0);">删除</a> </div>
首重费用：<input name="ex_firstprice[]" type="text" class="imin" /> 续重费用：<input name="ex_continueprice[]" type="text" class="imin" /> <input id="erl_for_[ID]" name="ex_regions[]" type="hidden" class="ilng" /><br/>
配送地区：
<br/> <font id="erl_font_[ID]"></font> <br/> <a href="javascript:regionSelector('[ID]');">添加地区</a> </div> </li> </ul> <a href="javascript:ex_regions_add();">添加新配送地区</a> </td> </tr> <tr> <td>详细介绍</td> <td> <textarea name="detail"><? echo htmlspecialchars($c['detail']); ?></textarea> </td> </tr> <tr> <td>优先级</td> <td> <input name="order" type="text" class="imin" value="<?=$c['order']?>" />
&nbsp;&nbsp;&nbsp;* 数字越大优先级越高
</td> </tr> <tr> <td>状态</td> <td> <label><input name="enabled" type="radio" class="iradio" value="true"
<? if($c['enabled']=='true') { ?>
 checked="true"
<? } ?>
 />启用</label> <label><input name="enabled" type="radio" class="iradio" value="false"
<? if($c['enabled']=='false' || empty($c['enabled'])) { ?>
 checked="true"
<? } ?>
 />关闭</label> </td> </tr> <tr class="footer"> <td colspan="4"> <input type="submit" value="保存" class="back1 back2"/> </td> </tr> </table> </form> 
<? include handler('template')->file('@admin/footer'); ?>