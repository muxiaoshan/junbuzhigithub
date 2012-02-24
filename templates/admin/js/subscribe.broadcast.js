var __g_class = '';
var __g_tid = '';

function pushd(clsname, tid)
{
	__g_class = clsname;
	__g_tid = tid;
	$('#status').html('');
	tb_show('Broadcast Pushd', '#TB_inline?height=50&width=450&inlineId=pushdSpace', false);
}

function pushdRequest()
{
	var cityID = $('#citySel').val();
	var url = '?mod=subscribe&code=push&class='+__g_class+'&tid='+__g_tid+'&city='+cityID+$.rnd.stamp();
	$('#status').html('loading...');
	$.get(url, function(data){
		if (data == 'ok')
		{
			$('#status').html('<font class="f_success">推送成功！</font>');
			setTimeout(function(){
				tb_remove();
			}, 1000);
		}
		else
		{
			$('#status').html('<font class="f_failed">'+data+'</font>');
		}
	});
}
