<?=ui('loader')->addon('uploader.swf')?>
<div id="ups_flash">
<div id="fileUploaderQueue"></div>
<input id="fileUploader" type="file" name="Filedata" />
<input id="fileUploaderButton" type="button" value="开始上传" style="position:relative; bottom:14px;*bottom:12px; width:100px;height:25px;font-weight:bold; font-family:'微软雅黑'; font-size:14px; " />
</div>
<div id="ups_normal" style="display:none;">
<iframe id="ups_iframe" frameborder="0" style="width:360px;height:60px;"></iframe>
</div>
<p>
*如果您不能上传图片，可以<a href="?#void" onclick="javascript:ups_Switch();return false;">点击这里</a>尝试普通模式上传
</p>
<? @session_start() ?>

<? $CCPRE = ini('settings.cookie_prefix') ?>
<script type="text/javascript">
var upcfg = {
'uploader'       : 'static/addon/SwfUploader/swfupload.swf',
'script'         : '<? echo urlencode("index.php?mod=upload&code=image"); ?>',
'cancelImg'      : 'static/addon/SwfUploader/cancel.png',
'buttonImg'      : 'static/addon/SwfUploader/button.png',
'folder'         : '/uploads',
'multi'          : true,
'auto'           : false,
'fileExt'        : '<?=$allowExts?>',
'sizeLimit'      : '<? echo $allowSize*1024; ?>',
'fileDesc'       : '请选择需要上传的文件',
'queueID'        : 'fileUploaderQueue',
'queueSizeLimit' : 12,
'simUploadLimit' : 3,
'removeCompleted': true,
'scriptData'     : {
'PHPSESSID': '<? echo session_id(); ?>',
'<?=$CCPRE?>sid': '<? echo base64_encode($_COOKIE[$CCPRE."sid"]); ?>',
'<?=$CCPRE?>auth': '<? echo base64_encode($_COOKIE[$CCPRE."auth"]); ?>',
'<?=$CCPRE?>ajhAuth': '<? echo base64_encode($_COOKIE[$CCPRE."ajhAuth"]); ?>',
'HTTP_USER_AGENT': '<? echo base64_encode($_SERVER["HTTP_USER_AGENT"]); ?>',
'HTTP_X_REQUESTED_WITH': 'xmlhttprequest'
},
'onComplete'     : function (event, queueId, fileObj, response, data)
{
try
{
eval('var data='+response);
}
catch(e)
{
$('#fileUploader').uploadifyFailed(queueId, '服务端处理错误！');
return false;
}
if (data.status == 'fails')
{
$('#fileUploader').uploadifyFailed(queueId, data.msg);
return false;
}
$('#fileUploader').uploadifySucceed(queueId, '上传成功！');
$.hook.call('swfuploaded', data.file);
return false;
}
};
$(document).ready(function(){
$('#fileUploader').uploadify(upcfg);
$('#fileUploaderButton').bind('click', function(){
$('#fileUploader').uploadifyUpload();
});
});
function ups_Switch()
{
if ($('#ups_normal').css('display') == 'none')
{
$('#ups_normal').css('display', 'block');
$('#ups_flash').css('display', 'none');
ups_if_reload();
}
else
{
$('#ups_normal').css('display', 'none');
$('#ups_flash').css('display', 'block');
}
}
function ups_Result(data)
{
if (data.status == 'fails')
{
$.notify.alert(data.msg);
}
else
{
$.hook.call('swfuploaded', data.file);
}
ups_if_reload();
}
function ups_if_reload()
{
$('#ups_iframe').attr('src', 'index.php?mod=upload&code=ui&driver=iframe');
}
</script>