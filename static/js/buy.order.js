var _allow_to_submit = true;

$(document).ready(function(){
	
});

function fizinit()
{
	$('#order_submit').bind('submit', function(){return order_submit()});
}

function order_field_append(name, value)
{
	$('#order_id').after('<input type="hidden" name="'+name+'" value="'+value+'" />');
}

function order_submit()
{
	$.hook.call('order_submit');
	if (_allow_to_submit)
	{
		$('#order_submit').ajaxSubmit({
			beforeSubmit: function()
			{
				$('#order_submit_button').attr('disabled', true);
				$('#submit_status').text('正在为您配置支付方式，请稍候...');
				$('#submit_status').css('display', 'inline');
			},
			success: function(data)
			{
				try {
					eval('var data='+data);
				} catch(e) {
					$('#submit_status').text('服务端错误，请重试！');
					return;
				}
				if (data.status != 'ok')
				{
					$('#submit_status').text(data.msg);
				}
				else
				{
					$('#submit_status').text('已经完成配置，正在跳转到支付页面...');
					order_finish();
				}
				$('#order_submit_button').attr('disabled', false);
			}
		});
	}
	return false;
}
