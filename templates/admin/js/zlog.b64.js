/**
$(document).ready(function(){
	$.each($('b64'), function(i, n){
	   var b64 = $(n).html();
	   if (zlog_b64_cache[b64] != undefined)
	   {
			$(n).html(zlog_b64_cache[b64]);
	   }
	   else
	   {
			$(n).html(b64+'.loading...');
			$.get('?mod=zlog&code=b64dec&string='+b64, function(data){
				zlog_b64_cache[b64] = data;
				$(n).html(data);
			});
	   }
	});
});