<?=ui('loader')->css('#html/isearcher/style')?>
<?=ui('loader')->js('#html/isearcher/main')?>
<div class="isearcher">
<select id="iscp_fids" class="isearcher_select_list" >
<? if(is_array($fids)) { foreach($fids as $fid) { ?>
<option value="<?=$fid?>"><?=$map[$fid]['name']?></option>
<? } } ?>
</select>
<input id="iscp_input" class="isearcher_input_words" type="text"/>
<input id="iscp_search" type="hidden" />
<input id="iscp_button" class="isearcher_submit_button" type="button" value="搜 索" />
<div id="iscp_iresult" class="isearcher_instant_result">
<ul id="iscp_iresult_list">
</ul>
</div>
<? if($ffsString) { ?>
<font class="separator">|</font>
筛选：
<? $frcKeys = '' ?>
<? if(is_array($frcs)) { foreach($frcs as $fcid) { ?>
<? if(!$filter[$fcid]) { ?>
<? continue ?>
<? } else { ?><? $frc = $filter[$fcid] ?>
<? } ?>
<? $frcKeys .= $frc['key'].',' ?>
<?=$frc['name']?>
<select id="iscp_frc_<?=$frc['key']?>" key="<?=$frc['key']?>" class="isearcher_filter_list">
<option value="###">全部数据</option>
<? if(is_array($frc['list'])) { foreach($frc['list'] as $val => $key) { ?>
<option value="<?=$val?>"><?=$key?></option>
<? } } ?>
</select>&nbsp;
<? } } ?>
<? } ?>
</div>
<script type="text/javascript">
var frcKeys = '<? echo substr($frcKeys, 0, -1); ?>';
</script>