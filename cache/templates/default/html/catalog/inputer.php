<input type="hidden" name="__catalog_subclass_old" value="<?=$category?>" />
<script type="text/javascript">var catalog_product_category = '<?=$category?>';</script>
<?=ui('loader')->js('#html/catalog/catalog.inputer')?>
<select id="__catalog_topclass" name="__catalog_topclass" onchange="catalog_master_change()">
<? if(is_array($catalog)) { foreach($catalog as $i => $topclass) { ?>
<option value="<?=$topclass['id']?>"
<? if($master['parent']==$topclass['id']) { ?>
 selected="selected"
<? } ?>
><?=$topclass['name']?></option>
<? } } ?>
</select>
<select id="__catalog_subclass" name="__catalog_subclass">
<option value="-1">正在加载</option>
</select>
<a href="?#void" onclick="catalog_subclass_add();return false;">添加子分类</a>
<script type="text/javascript">catalog_master_change();</script>
<script type="text/javascript">
$.hook.add('catalog.add.finish', function(id){
catalog_product_category = id;
catalog_master_change();
});
</script>